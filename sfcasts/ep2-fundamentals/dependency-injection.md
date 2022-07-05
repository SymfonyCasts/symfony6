# Dependency Injection

Our `MixRepository` is *sort of* working. We can autowire it into our controller, and the container is *instantiating* that and passing it to us. We *prove* that over here, because when we run that code, it's successful calling the `findAll()` method, but then it's exploding inside. That's because, inside `MixRepository`, in order to do our work, we need two services: The `$cache` service and the `$httpClient` service.

I keep saying that there are many services floating around inside of Symfony, waiting for us to use them. That's *true*, but you can't just grab them out of thin air from anywhere in your code. There's no `Cache::get()` static method that you can call anywhere in your code to get the `$cache` object. Nothing like that exists in Symfony. And that's good! Allowing us to grab objects out of thin air is a recipe for writing bad code. So how *can* we get access to these services? Currently, we only know one way: By autowiring them into our controller. *But* that won't work here. Autowiring services is a superpower that *only* works for controller methods.

Watch this. If I said `CacheInterface` here, then went over and refreshed, you'd see:

`Too few arguments to function [...]findAll(),
0 passed [...] and exactly 1 expected.`

That's because *we* are calling `findAll()`, so if we're going to pass any arguments in, it's going to be *our* responsibility. The point is, autowiring works in controller methods, but don't expect it to work anywhere else.

*But* one way we might get this work is by passing *both* services to the `findAll()` method and then *manually* passing them into the controller ourselves. This won't be the final solution, but let's try it.

I already have a `CacheInterface` argument. Also going to add the `HttpClientInterface` argument, and call that `$httpClient`. Perfect! This method is now *happy*.

Back over in our controller, you can see that our `findAll()` method has two arguments. Let's pass `$httpClient` and `$cache`. And now... it works! So on a high level, this solution makes sense. We know that we can autowire services into our controller, so then we just pass them into `MixRepository`. But if you think about it, the `$httpClient` and `$cache` services aren't really input to the `findAll()` function.

For example, pretend that we decide to change the `findAll()` method to accept a `string $genre` argument so this function will *only* return mixes for that genre. This argument makes perfect sense. We could call `findAll()` and control its behavior by passing it different genres. The argument controls how the method behaves. *But* the `$httpClient` and `$cache` arguments *don't* really control how the function behaves. In reality, you would pass these same two values every time you call the method. Instead of arguments, these are really more of *dependencies* that the service needs in order to do its work. They're just stuff that must be available so that `findAll()` can do its job. For dependencies like this, service objects, or configuration that your service needs to do its job, instead of passing them to the methods, we pass them into the constructor. So delete that, pretend it's a `$genre` argument, and then add a `public function __construct()` method. Then, copy the two arguments, delete them, and move them up here.

Before I finish this, I need to tell you that autowiring works in *two* places. We already know that you can autowire arguments into your controller methods, but you can *also* autowire arguments into the `__construct()` method of your services. In fact, that's the main place where autowiring is meant to work. The fact that autowiring works for controller methods was kind of added later just to make life easier. And as we saw earlier, this is the only normal method where you can do that.

Anyway, autowiring works in the `__construct()` method of our services, so as long as we type hint the arguments (and we have), when symfony instantiates our service, it will pass us these two services. Yay! What do we *do* with these two arguments? We set them on the properties.

Create a `private $httpClient` property and a `private $cache` property. Then, down in the constructor, you just assign them `$this->httpClient = $httpClient`, and `$this->cache = $cache`. So when Symfony instantiates our `MixRepository`, it passes us these two arguments and we store them on a property so we can use them later. Watch! Down here, instead of `$cache`, we can say `$this->cache`. And then we don't need this `use ($httpClient)`over here because, inside of here, we can say `$this->httpClient`. *Now* this service is in perfect shape.

Back over in `VinylController.php`, we can simplify things. This `findAll()` doesn't need any arguments anymore. Woo! And we don't need to autowire `$httpClient` or `$cache` anymore. And I'm going to celebrate a little extra, even though I don't need to, by removing those `use` statements on top. Look how much easier that is! We autowire the *one* service we need, call the method on it, and... it even *works*! So this is a perfect way to write a service. We add our dependencies to the constructor, set them onto properties, and then *use* them. By the way, what we just did has a fancy schmmancy name: "Dependency injection". But don't run away! That may be a scary term, but it's a very simple concept.

When you're inside of a service like `MixRepository` and you realize you need *another* service as a dependency (or maybe some configuration like an API key), to get the thing you need, you add a constructor, add an argument for the thing you need, set it under a property, and then use it down in your code. Yep! *That's* dependency injection. Put simply, this dependency injection says:

`If you need something, instead of grabbing it out
of thin air, force Symfony to pass it to you via the constructor.`

This is one of the most fundamentally important things that you will do over and over again in Symfony.

Okay, unrelated to dependency injection and autowiring, there are two minor improvements that we can make to our service. The first is that we can add *types* to our properties: `HttpClientInterface` and `CacheInterface`. That doesn't change how any of our code works. It's just a nice, responsible way to do things. But we can go a little further! In PHP 8, there's a new, shorter syntax for creating a property and setting it in the constructor, like we've been doing. It looks like this. First, I'm going to put my arguments onto multiple lines. Then, we add `private` in front of each of the arguments. And *then*, I'll delete the properties as well as the inside of the method. That might look weird at first, but as soon as you add `private`, `protected`, or `public` in front of a `__construct()` argument, that actually creates a property with this name and sets this argument to that property. So it looks different, but it's the *exact* same as what we had before. And when we try it... it still works!

Next: I keep saying that the container holds *services*. That's *true*, but it also holds one other thing - simple configuration called "parameters".

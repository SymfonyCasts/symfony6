# Dependency Injection

Our `MixRepository` service is *sort of* working. We can autowire it into our
controller and the container is *instantiating* the object and passing it to us.
We *prove* that over here because, when we run the code, it successfully calls
the `findAll()` method.

But.... then it explodes. That's because, inside `MixRepository` we have two
undefined variables. In order for our class to do its job, it needs two services: the
`$cache` service and the `$httpClient` service.

## Autowiring to Methods is a Controller-Only Superpower

I keep saying that there are many services floating around inside of Symfony, waiting
for us to use them. That's *true*. *But*, you can't just grab them out of thin air
from anywhere in your code. For example, there's no `Cache::get()` static method
that you can call whenever you want that will return the `$cache` service object.
Nothing like that exists in Symfony. And that's good! Allowing us to grab objects
out of thin air is a recipe for writing bad code.

So how *can* we get access to these services? Currently, we only know one way: by
autowiring them into our controller. *But* that *won't* work here. Autowiring services
into a *method* is a superpower that *only* works for controllers.

Watch: if we added a `CacheInterface` argument... then went over and refreshed,
we'd see:

> Too few arguments to function [...]findAll(), 0 passed [...] and exactly 1 expected.

That's because *we* are calling `findAll()`. So if `findAll()` needs an argument,
it is *our* responsibility to pass them: there's no Symfony magic. My point is:
autowiring works in controller methods, but don't expect it to work for any
*other* methods.

## Manually Passing Services to a Method?

*But* one way we *might* get this to work is by adding both services to the
`findAll()` method and then *manually* passing them in from the controller. This
won't be the final solution, but let's try it.

I already have a `CacheInterface` argument... so now add the
`HttpClientInterface` argument and call it `$httpClient`:

[[[ code('bbbc6e904f') ]]]

Perfect! The code in this method is now happy.

Back over in our controller, for `findAll()`, pass `$httpClient` and `$cache`:

[[[ code('4592179cbd') ]]]

And now... it works!

## "Dependencies" Versus "Arguments"

So, on a high level, this solution makes sense. We know that we can autowire services
into our controller... and then we just pass them into `MixRepository`. But if you
think a bit deeper, the `$httpClient` and `$cache` services aren't really *input*
to the `findAll()` function. They don't really make sense as arguments.

Let's look at an example. Pretend that we decide to change the `findAll()` method
to accept a `string $genre` argument so the method will *only* return mixes for
*that* genre. This argument makes perfect sense: passing different genres changes
what it returns. The argument *controls* how the method *behaves*.

*But* the `$httpClient` and `$cache` arguments *don't* control how the function
behaves. In reality, we would pass these *same* two values *every* time we call the
method... *just* so things *work*.

Instead of arguments, these are really *dependencies* that the service *needs*.
They're just stuff that *must* be available so that `findAll()` can do its job!

## Dependency Injection & The Constructor

For "dependencies" like this, whether they're service objects or static
configuration that your service needs, instead of passing them to the methods,
we pass them into the *constructor*. Delete that pretend `$genre` argument... then
add a `public function __construct()`. Copy the two arguments, delete them, and
move them up here:

[[[ code('88ae2b61fb') ]]]

Before we finish this, I need to tell you that autowiring works in *two* places. We
already know that we can autowire arguments into our controller methods. But we
can *also* autowire arguments into the `__construct()` method of any service. In
fact, that's the *main* place that autowiring is meant to work! The fact that
autowiring also works for controller methods is... kind of an "extra" just to make
life nicer.

Anyways, autowiring works in the `__construct()` method of our services. So as long
as we type-hint the arguments (and we have), when Symfony instantiates our service,
it will pass us these two services. Yay!

And what do we *do* with these two arguments? We set them onto properties.

Create a `private $httpClient` property and a `private $cache` property. Then, down
in the constructor, assign them: `$this->httpClient = $httpClient`, and
`$this->cache = $cache`:

[[[ code('811c0bad31') ]]]

So when Symfony instantiates our `MixRepository`, it passes us these two arguments
and we store them on properties so we can use them later.

Watch! Down here, instead of `$cache`, use `$this->cache`. And then we don't need
this `use ($httpClient)` over here... because we can say `$this->httpClient`:

[[[ code('f8d4d7c4cd') ]]]

This service is now in *perfect* shape.

Back over in `VinylController`, now we can simplify! The `findAll()`
method doesn't need any arguments... and so we don't even need to autowire
`$httpClient` or `$cache` at all. I'm going to celebrate by removing those `use`
statements on top:

[[[ code('6d017b765c') ]]]

Look how much easier that is! We autowire the *one* service we need, call the method
on it, and... it even *works*! *This* is how we write services. We add any
dependencies to the constructor, set them onto properties, and then *use* them.

## Hello Dependency Injection!

By the way, what we just did has a fancy schmmancy name: "Dependency injection".
But don't run away! That may be a scary... or at least "boring sounding" term, but
it's a very simple concept.

When you're inside of a service like `MixRepository` and you realize you need
*another* service (or maybe some config like an API key), to get it, create a
constructor, add an argument for the thing you need, set it onto a property, and
then use it down in your code. Yep! *That's* dependency injection.

Put simply, dependency injection says:

> If you need something, instead of grabbing it out of thin air, force Symfony to
> pass it to you via the constructor.

This is one of *the* most important concepts in Symfony... and we'll do this over
and over again.

## PHP 8 Property Promotion

Okay, *unrelated* to dependency injection and autowiring, there are two minor
improvements that we can make to our service. The first is that we can add *types*
to our properties: `HttpClientInterface` and `CacheInterface`:

[[[ code('157fc6f3fd') ]]]

That doesn't change how our code works... it's just a nice, responsible way to do things.

But we can go further! In PHP 8, there's a new, shorter syntax for creating
a property and setting it in the constructor like we're doing. It looks like
this. First, I'll move my arguments onto multiple lines... just to keep things
organized. Now add the word `private` in front of each argument. Finish by
deleting the properties... as well as the inside of the method.

That might look weird at first, but as soon as you add `private`, `protected`, or
`public` in front of a `__construct()` argument, that creates a property with this
name and sets the argument *onto* that property:

[[[ code('a7cff8b189') ]]]

So it looks different, but it's the *exact* same as what we had before.

When we try it... yup! It still works.

Next: I keep saying that the container holds *services*. That's *true*! But it also
holds one other thing - simple configuration called "parameters".

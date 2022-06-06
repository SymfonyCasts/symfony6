# Dependency Injection

Coming soon...

Our MixRepository is sort of working. We can autowire it into Our controller and the
container is instantiating that and passing it to us. We prove that over here,
because when we run that code, It is successful calling the findAll method, but then
it's exploding inside that's because inside MixRepository, in order to do our work,
we need two services, the cache service and the httpClient service real quick. I keep
saying that there are many services floating around inside of symfony waiting for us
to use them. That's true, but you can't just grab them out of thin air from anywhere
in your code. There's no like `Cache::get()` method Static method that you can call
anywhere in your code to get the cache object, nothing like that exists in symfony.
And that's good allowing us to grab objects out of thin air is a recipe for writing
bad code. So how can we get access to these services? Currently, we only know one way
by autowiring them into our controller, But that won't work here. That won't work
here. Autowiring services is a superpower that only works for controller methods.

So don't try to use it anywhere else. Watch if I did like (CacheInterface) here And
went over and refreshed, you'd see Too few arguments to function findAll. 0 passed
one expected that's because we are the ones that are calling findAll. So if we're
going to pass any arguments in, it's going to be our responsibility. So the point is
autowiring works in controller methods, but don't expect it to work anywhere else.
But One idea to get this work is that we could pass both services to the findAll
method and then manually pass them in ourselves. And the controller. This won't be
the final solution, but let's try it. So already I have a 'CacheInterface' argument.
Also going to add the 'HttpClientInterface' argument, and call that HttpClient and
perfect. This method is now happy Back over in our controller. You can see that our
findAll method has two arguments. So let's pass a HttpClient and cache, And now It
works So on a high level. This solution makes sense. We know that we can autowire
services into our controller. So then we just pass them into MixRepository. But if
you think about it,

The HttpClient and cache services, aren't really input to the findAll function. For
example, pretend that we decide to change the findAll method to accept a (string
$genre) argument. And then this function will only return mixes for that genre. This
argument makes perfect sense. We could call findAll and control its behavior by
passing it different genres. The argument controls how the method behaves, but the
HttpClient and cache arguments don't really control how the function behaves you
would. In reality, you would pass these same two values. Every time you call the
method, Nope. Instead of arguments, These are really dependencies that the service
needs in order to do its work. They're just stuff that must be available so that
findAll can do its job for dependencies like this, for service objects, or
configuration that your service simply needs to do its job. Instead of passing them
to the methods, we pass them into the construction. So delete that, pretend it's
genre argument, and then add a `public function__construct()` method And then let's
move. Copy the two arguments, delete them and move them up here. Now, before I finish
this, I need to tell you that autowiring works in two places. We already know that
you can autowire arguments into your controller methods, but you can also autowire
arguments into the construct method of your services. In fact, that's the main place
where autowiring is meant to work. The fact that autowiring works for controller
methods was kind of added later just to make life easier. And as we saw earlier, this
is the only normal method where you can do that.

Anyways, autowiring works in the construct method of our services. So as long as we
type in the arguments which we have, when symfony instantiates our service, it will
pass us these two services. Yay. What do we do with these two arguments? We set them
on the properties, create a `private $httpClient:` property and a `private $cahe:`
property. And then down the constructor, you just assign them `$this->httpClient =
$httpClient`, and then `$this->cache = $cache`. So when symfony and Stain shades are
MixRepository, it passes us these two arguments and we destroy them on a property
store them on property so that we can use them later. Watch down here. Now, instead
of cache, we can say `$this->cache`, And then we don't need the use over here for
httpClient because inside of here, we can say `$this->httpClient`, And now this
service is in perfect shape. Back over in VinylController. We can simplify things,
findAll, doesn't have any methods and doesn't ha to have any arguments anymore. Yay.
And we don't need to autowire hate to httpClient or cache anymore.

I'm even going to extra celebrate, even though I don't need to by removing those use
statements on top. So look how much easier that is now in our controller, we oi the
one service we need call the method on it And It even works. So this is a perfect way
to write a service, Add our dependencies to the constructor, set them onto
properties, and then use them by the way, what we just did has a fancy schmmanzy
name, dependency injection. Ooh, no, don't run away. That's a scary or maybe boring
term for a very simple concept. Whenever you're inside of a service like
MixRepository and you realize you need another service as a dependency, or maybe Some
configuration like an API key to get that thing you need, you add a construct meth,
add a constructor Add an argument for the thing you need set it under a property and
then use it down in your code. Yep. That's dependency and Yep. That's dependency
injection. A simple way to say it is this dependency. Injection says, if you need
something, instead of grabbing it out of thin air forced symfony to pass it to you
via the constructor,

This is one of the most fundamentally important things that you will do over and over
again in symfony. Okay. Now unrelated to dependency injection and autowiring. There
are two minor improvements that we can make to our service. The first is that we can
add types to our properties `httpClientInterface` and `CacheInterface` That doesn't
change how any of our code works. It's just a nice, responsible way to do things, but
we can go a little further in php 8, there's a new, shorter syntax for creating a
property and setting it down in the constructor like we're doing, it looks like this
first. I'm going to pop my, put my arguments onto multiple lines. And then instead of
having the property up here, we kind of create the property. We had private in front
of each of the arguments, And then I'll delete the properties and even delete the
inside of the method. Yep. That might look weird at first, but as soon as you add the
a private protected or public in front of a construct arguments that actually, um,
Creates a property with this name and sets this argument to that property.

So this looks different, but it is the exact same as what we had before. And when we
try it, it still works. All right. Next, I keep saying that the container holds
services that's true, but it also holds one other thing, simple configuration called
parameters.

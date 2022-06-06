# Create Service

Coming soon...

We know that bundles give us services and services do work. So if we need to write
our own custom code that does work, can we put that into our own service? Absolutely.
And that's a great way to organize your code, allow your logic to be reused and will
let you unit test your code. If you want to. We're already doing some work in our app
in the browse action. We make the http request and cache the result Putting this
logic in our controller is fine, but by moving it into its own service class, it'll
make the code more clear and reusable. So let's do it. How do we create a service in
the source directory, create a new Php class anywhere seriously, It doesn't matter
how you organize things. What sub directories you create. You can do whatever you
want, but pretty commonly I'll have a service directory. And inside of that, let's
create a new peach class I'll call. How about MixRepository, where repository is a
pretty common name for a, uh, service that returns data. Now I notice when I create
this php storm automatically added the correct namespace. It doesn't matter how we
organize our services inside of source. As long as wherever we put it, our namespace
matches our directory.

Now, one important thing about service classes are that they have nothing to do with
Symfony. Our controller class is a Symfony concept, But this MixRepository is 100% a
class that we are creating to organize our own logic. So there aren't any rules. We
don't need to extend a base class or implement an interface. We can make this class
look and feel however we want. So how about let's create a new public function called
`findAll():` that will return an array of all of the mixes in our system. Back in the
controller, copy all the logic that fetches the mixes and paste that feature storm
will ask you if you want to add a use statement for the cache item interface hit.
Okay. To add that on top and then instead of creating a mixes variable, let's just
return this Ya. Yes, there are some undefined variables in this class. Don't worry
about these yet. I first want to see if we can use our Mix Repository and in order to
use the MixRepository inside of VinylController, We need to a, We need to add it to
the symfony's container so that we can autowire. It

Like we did with htttpClient and CacheInterface. Well, I have a surprise spin over
your terminal and run `bin/console debug:autowiring --all`, And then scroll up to the
top. Surprise. Our MixRepository is already a service in the container. So let me
explain two things. First, the `--all` flag is not that important. It basically tells
this command to show you the core services like HttpClient and cache, plus our own
services like MixRepository second. Yes, the container somehow already saw our
repository class and recognized it as a service. How did that happen? We'll learn
about that in a few minutes for now. It's enough to know that our new MixRepository
is already inside the container, which means we can autowire it Back over in our
controller. Let's add a third argument type end with mixRepository and call it. How
about mixRepository Then down here, we don't need any of this mixes code anymore. Cuz
we can simply say `$mixes = $mixRepository->findAll()` how nice is that? All right.
Let's try it refresh and yes. Okay. Undefined variable coming from our mixRepository,
but it works. The fact that our code got here means that when we autowire
mixRepository, the container instantiated our mix repository passed it to us so that
we could then call a method on it.

We created a service and made it available for autowiring. Cool. But our new service
needs the HttpClient and cache services in order to do its work. How could we get
those from inside of here? The answer is one of the most important concepts in
symfony and object oriented coding in I general dependency injection. Let's talk
about that next.

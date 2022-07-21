# Creating a Service

We know that bundles give us services and services do work. Ok. But what if we need
to write our *own* custom code that does work? Should we... put that into our *own*
service class? Absolutely! And it's a *great* way to organize your code.

We *are* already doing some work in our app. In the `browse()` action: 

[[[ code('51d407c154') ]]]

we make an HTTP request and cache the result. Putting this logic in our controller is *fine*.
But by moving it into its own service class, it'll make the *purpose* of the code
more clear, allow us to reuse it from multiple places... and even enable us to
unit test that code if we want to.

## Creating the Service Class

That sounds *amazing*, so let's do it! How do we create a service? In the `src/`
directory, create a new PHP class *wherever* you want. It seriously doesn't matter
what directories or subdirectories you create in `src/`: do whatever feels good
for you.

For this example, I'll create a `Service/` directory - though again, you could
call that `PizzaParty` or `Repository` - and inside of *that*, a new
PHP class. Let's call it... how about `MixRepository`. "Repository" is a pretty common
name for a service that returns data. Notice that when I create this, PhpStorm
*automatically* adds the correct namespace. It doesn't matter *how* we organize our
classes inside of `src/`... as long as our namespace matches the directory:

[[[ code('dab4aa0947') ]]]

One important thing about service classes: they have *nothing* to do with Symfony.
Our controller class is a Symfony concept. But `MixRepository` is a class *we're*
creating to organize our *own* code. That means... there are no rules! We don't
need to extend a base class or implement an interface. We can make this class look
and feel however we want. The power!

With that in mind, let's create a new `public function` called, how about,
`findAll()` that will `return` an `array` of all of the mixes in our system. Back
in `VinylController`, copy all of the logic that fetches the mixes and paste that
here:

[[[ code('3085dac9b6') ]]]

PhpStorm will ask if we want to add a `use` statement for the `CacheItemInterface`. 
We *totally* do! Then, instead of creating a `$mixes` variable, just `return`:

[[[ code('eebdfc0b0f') ]]]

There *are* some undefined variables in this class... and those *will* be a problem.
But ignore them for a minute: I *first* want to see if we can *use* our shiny new
`MixRepository`.

## Is our Service already in the Container?

Head into `VinylController`. Let's think: we somehow need to tell Symfony's service
container about our new service so that we can then *autowire* it in the same way
we're autowiring core services like `HtttpClientInterface` and `CacheInterface`.

Whelp, I have a surprise! Spin over to your terminal and run:

```terminal
php bin/console debug:autowiring --all
```

Scroll up to the top and... amaze! `MixRepository` is *already* a service in
the container! Let me explain two things here. First, the `--all` flag is not *that*
important. It *basically* tells this command to show you the core services like
`$httpClient` and `$cache`, *plus* our own services like `MixRepository`.

Second, the container... *somehow* already saw our repository class and recognized it
as a service. We'll learn *how* that happened in a few minutes... but for now, it's
enough to know that our new `MixRepository` *is* already inside the container *and*
its service *id* is the full class name. *That* means we can autowire it!

## Autowiring the new Service

Back over in our controller, add a third argument type-hinted with `MixRepository` -
hit tab to add the `use` statement - and call it... how about `$mixRepository`:

[[[ code('a05b30f0d4') ]]]

Then, down here, we don't need any of this `$mixes` code anymore. Replace it with
`$mixes = $mixRepository->findAll()`:

[[[ code('c5fdb1c634') ]]]

How nice is that? Will it work? Let's find out! Refresh and... it *does*! Ok,
working in this case means that we get an `Undefined variable $cache` message
coming from `MixRepository`. But the fact that our code *got* here means
that autowiring `MixRepository` *worked*: the container saw this, *instantiated*
`MixRepository` and passed it to us so that we could use it.

So, we created a service and made it available for autowiring! We are *so* cool!
But our new service needs the `$httpClient` and `$cache` services in order to do
its job. How do we get those? The answer is one of the *most* important concepts
in Symfony and object-oriented coding in general: dependency injection. Let's talk
about that next.

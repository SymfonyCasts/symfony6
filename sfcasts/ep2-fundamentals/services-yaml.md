# All About services.yaml

When Symfony first boots up, it needs to get the full list of all of the services
that should be in the container. That includes the service ID, its class name, and
all of its constructor arguments. The first and biggest source of services are
*bundles*. If you run

```terminal
php bin/console debug:container
```

the vast majority of these services come from bundles. The *second* place the
container gets services from is *our* code. And to learn about our services, Symfony
reads `services.yaml`.

## The Special \_defaults Section

At the moment that Symfony starts parsing the first line of this file, *nothing*
in our `src/` directory has been registered as a service in the container. This is
really important. Adding our classes to the container is, in fact, the *job* of
this file! And the way it does it is pretty amazing. Let's take a tour!

Notice that the config is under a `services` key. Like `parameters`, this
is a special key. And, like its name suggests, anything under this is meant to
configure *services*.

The first *sub-key* under this is `_defaults`. `_defaults` is a magic key
that allows us to define some *default* options that will be added to *all* services
that are registered in this file. So *every* service that we register below will
*automatically* have `autowire: true` and `autoconfigure: true`:

[[[ code('a83c31b72c') ]]]

Let's look at an example. The most *basic* thing you can do under the `services`
key is... register a service! That's what we're doing at the bottom. This tells
the container that there should be an `App\Service\MixRepository` service in the
container *and* we specified one option: `bind`.

[[[ code('11d276a2d8')]]]

Services can actually have a *bunch* of options, including `autowire` and
`autoconfigure`. So it would be *totally* legal to say, `autowire: true` and
`autoconfigure: true` right here. This would work *just* fine. But thanks to the
`_defaults` section, those aren't needed! The `_defaults` says:

> Unless it's been overridden on a specific service, set `autowire` and
> `autoconfigure` to `true` for all services in this file.

And what does `autowire` *do*? Simple! It tells Symfony's container:

> Hey! Please try to guess my constructor arguments by looking at their type-hints.

This feature is pretty awesome... which is why it's automatically turned on for all
of our services. The other option - `autoconfigure` - is more subtle and we'll talk
about it later.

## Service Auto-Registration

All right, by the time we get to the `_defaults` line, we've established some default
configuration... but we *haven't* actually registered any services yet. That's the
job of the next section... and it's the key to *everything*:

[[[ code('dee9765bcf') ]]]

This special syntax says

> Please look inside the `src/` directory and automatically register *all*
> PHP classes as a service... except for these three things.

This is why, *immediately* after we created the `MixRepository` class, it was
*already* in the container! And thanks to the `_defaults` section, any services
registered by this will *automatically* have `autowire: true` and
`autoconfigure: true`. That's some serious team work! This mechanism is called
"Service Auto-Registration".

But remember, every service in the container needs to have a unique ID. If you look
back at `debug:container`, most of the service IDs are snake case. Let me zoom out
a bit so it's easier to see. Better! So, for example, the `Twig` service has the
snake case `twig` ID. But if you scroll up to the top of this list, our
`MixRepository` ID is... the full *class* name.

Yep! When you use Service Auto-Registration, it uses the class name as *both* the
class *and* the service *ID*. This is done for simplicity... but *also* for
autowiring. When we try to autowire `MixRepository` into our controller or anywhere
else, to figure out which service to pass us, Symfony will look for a service
whose ID exactly matches `App\Service\MixRepository`. So Service
Auto-Registration not only registers our classes as services, it does it in a way
that makes them *autowireable*. That's awesome!

## Auto-Registration of Non-Services?

Anyway, after this section here, *every* class in `src/` is now registered
as a service in the container. Except, well... we don't want *every* class in
`src/` to be a service.

There are really two types of classes in your app: "Service classes" that do work,
and "model classes" - sometimes called "DTOs" - whose job is mostly to hold data -
like a `Product` class with `name` and `price` properties. We want the container
to handle instantiating our services. But for model classes, *we* will create them
whenever we need them - like with `$product = new Product()`. So, these will *not*
be services in the container.

In the next tutorial, we'll create Doctrine entity classes, which are model classes
for the database. These will live in the `src/Entity/` directory... and since
they're not meant to be services, that directory is excluded. So we register
*everything* in the `src/` directory as a service, *except* for these three things.

But.. fun fact! This `exclude` key is *not* that important. Heck, you could delete
it and everything would *still* work! If you accidentally register something as a
service that isn't *meant* to be a service, *no worries*! Since you'll never try
to autowire and *use* that class like a service, Symfony will realize it's not
being used and remove it from the container. Dang, that is smart!

## Custom Service Configuration

So everything in `src/` is automatically registered as a service without us needing
to do anything or touch this file.

*But*... occasionally, you'll need to add *extra* config to a *specific* service.
That's what happened with `MixRepository` thanks to its non-autowireable
`$isDebug` argument.

To fix that, at the bottom of this file, we're registering a new service whose ID
and class is `App\Service\MixRepository`. This will actually *override* the service
that was created during Service Auto-Registration, since both IDs will match
`App\Service\MixRepository`. So, we're defining a *brand new* service.

But thanks to `_defaults`, it automatically has `autowire: true` and
`autoconfigure: true`. Then we add the additional `bind` option.

So the only thing we need to put at the bottom of this file are services that need
*additional* configuration to work. And... there's actually a *cooler* way to fix
non-autowireable arguments that I'll show you next.

## All Configuration Files are Equals!

But before we get to that, I want to mention one more thing: this file,
`services.yaml`, is loaded via the *same* system that loads all of the files in
`config/packages/`. In fact, there's no technical difference between this file and
say... `framework.yaml`. That's right! If we wanted to, we could copy and delete
the contents of `services.yaml`, paste them into `framework.yaml`, and everything
would work *exactly* the same.

*Except* that... we would need to, y'know, just correct these paths since we're one
directory deeper. Watch! I'll move this around real quick and... this still works
just fine! Cool! Let's put that back the way it was and... there we go.

The only reason we have a `service.yaml` file is for organization. It feels good to
have *one* file to "configure your services". The truly important thing is that all
of this config lives under the `services` key. In fact, near the top of this file,
you'll notice there's an empty `parameters` key.

In `cache.yaml`, we created a `parameters` key *there* to register a new parameter.
It's really up to us to decide *where* we want to define this parameter. We can do it
in `cache.yaml` or, to keep all parameters in one spot, we could copy this and move
it over to `services.yaml`.

In `cache.yaml`, I'll also grab the `when@dev`, delete that, and paste it into
`services.yaml`:

[[[ code('5ffe3e85e5') ]]]

On a technical level, that makes no difference and our app *still*
works. But I like this better. Services and parameters are a global idea in your
app... so it's nice to organize them all in one file.

All right, the only reason we wrote any code at the bottom of `services.yaml`
was to tell the container what to pass to the non-autowireable `$isDebug` argument.
But what if I told you there's a more *automatic* way to solve these problematic
arguments? That's *next*.

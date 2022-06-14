# Services Yaml

When Symfony first boots up, it needs to get the full list of all of the services that should be in the container. That includes the service ID, its class name, and all of its constructor arguments. The first and biggest source of services are *bundles*. If you run

```terminal
php bin/console debug:container
```

the vast majority of these services come from bundles. The *second* place the container gets services from is our *code*. To learn about our services, Symfony reads `services.yaml`. And when Symfony starts parsing the first line of this file, nothing in our `/src` directory has been registered as a service in the container. This is really important. Adding our classes to the container is, in fact, this file's *job*, and the way it does that is pretty amazing. Let's walk through it!

Notice that the service config is under a `services` key. And the first key under here is `_defaults`. This is a pretty fancy config key that defines some default options that will be added to *all* services that are registered in this file. So *every* service will *automatically* have `autowire: true` and `autoconfigure: true`.

Earlier, we set this up to manually register a service. We'll talk more about that in a minute. Right now, I want to focus on the `bind` option we saw. Services can, in fact, have a *bunch* of different options, including `autowire` and `autoconfigure`. So it would be *totally* legal for me to say, `autowire: true` and `autoconfigure: true` right here. This would work just fine, but thanks to the `_defaults` section, those are *not* needed. the `_defaults` says:

`Unless it's been specifically overridden on a
service, set 'autowire' and 'autoconfigure' to
'true' for all services in this file.`

And what does `autowire` *do*? Simple! It tells Symfony's container:

`Hey! Please try to guess my constructor arguments
by looking at their type-hints.`

This feature is pretty awesome, which is why it's automatically turned on for all of our services. The other option - `autoconfigure` - is more subtle and we'll talk about it later.

All right, by the time we get to this line here, we've established some default configuration, but we *haven't* actually registered any services yet. That's the job of the next section, and it's the key to *everything*. This special syntax says:

`Look inside the '/src' directory and automatically
register *everything* you see as a service,
except for these three things.`

This is why, immediately after we created the `MixRepository` class, it was *already* in the container. And thanks to the `_default` section, any services registered by this will *automatically* have `autowire: true` and `autoconfigure: true`. This mechanism is called Service Auto Registration.

But remember, every service in the container needs to have a unique ID. If you look back at `debug:container`, most of the service IDs are snake case. Let me zoom out a little so it's easier to see. Better! So for example, the `Twig` service has the snake case `twig` ID, but if you scroll up to the top of this list, our `MixRepository` ID is actually the *class* name. Yep! When you use Service Auto Registration, it uses the class name as *both* the class and the service ID. This is done for simplicity, but *also* for autowiring. When we try to autowire `MixRepository` into our controller or anywhere else, to figure out which service to pass us, Symfony will look for a service in a container whose ID matches `App\Service\MixRepository` *exactly*. So Service Auto Registration not only registers our classes as services, but does it in a way that makes them *autowireable* into other services. That's awesome!

Anyway, after this section here, we have now registered every class in `/src` as a service. Except, well... we don't *want* every class in `/src` to be a service. There are really two types of classes: "Service classes" that do work, and "model classes", sometimes called "DTOs", whose job is mostly just holding data. In the next tutorial, we'll create doctrine entity classes that will model the database. These will live in the `/Entity` directory and since they're not meant to be services, that directory will be ignored. So we register *everything* in the `/src` directory as a service, *except* for these three things.

Here's a couple of side notes: This `exclude` key isn't that important. Heck, you could delete it and everything would *still* work. And if you accidentally register something as a service that isn't meant to be a service, *no worries*. Since you'll never try to autowire and *use* that class like a service, Symfony will realize it's not being used and remove it from the container. Nice!

So *here's* the big takeaway. Everything in the `/src` directory is automatically registered as a service without us needing to think about it. You won't need to touch this file 99% of the time. *But* for `MixRepository`, we have a non-autowireable argument: `$isDebug`. At the bottom of this file, we're registering a new service whose ID and class is `App\Service\MixRepository`. This will actually *override* the service that was created during Service Auto Registration, since both IDs will match `App\Service\MixRepository`. So we're defining a *brand new* service down here, but thanks to `_defaults`, it automatically has `autowire: true` and `autoconfigure: true`. Then we add the additional `bind` option that we need to hint the argument. So the only thing you need to put at the bottom of this file are services that need *additional* configuration to work. There's actually a *cooler* way to fix the non-autowireable argument, and I'll show show it to you next.

But before we get to that, check this out. This file, `services.yaml`, is loaded via the *same* system that loads all of the files and `config\packages`. In fact, there's no technical difference between this file and say... `framework.yaml`. That's right! If we wanted to, we could copy and delete the contents of `services.yaml`, paste them into `framework.yaml`, and everything would work *exactly* the same. *Except* that we would need to, y'know, just correct these paths since we're one directory deeper. Watch! I'll move this around really quick and... this still works just fine! Cool! i'll put this back the way it was and... there we go.

The only reason we have a `service.yaml` file is for organization. It feels good to have *one* file to configure your services. The truly important thing is that all of this config lives under the `services` key. In fact, near the top of this file, you'll notice there's an empty `parameters` key. You might remember from earlier, in `cache.yaml`, we created a `parameters` key there to register a parameter. It's really up to us to decide *where* we want to define this parameter. We can do it in `cache.yaml` or, if we want to, we can copy this and move it over to `services.yaml`. In `cache.yaml`, I'll also grab the `when@dev`, delete that, and paste it into `services.yaml`. On a technical level, that makes no difference and our app *still* works. I actually like this better. Services and parameters are a global idea in your app, so it's nice to organize them all in one file.

All right, the only reason we've written any code at the bottom of our `services.yaml` file is to hint to the container what to pass to the non-autowireable `$isDebug` argument. But what if I told you there's a more *automatic* way to solve these problematic arguments? That's *next*.

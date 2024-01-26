# New Autowiring Attributes

So what's new in Symfony 7? Nothing! The real question is, what's new in Symfony 6.4?
Or maybe, what's new in 6.3 or 6.2 that... maybe we missed?

## Quick Tour of New Featuers

The best place to find this stuff, to be honest, is the Symfony blog. Javier does
a fantastic job with every single release, uncovering the most important features.

I've pulled up just a couple of my favorite, like the workflow profiler. If you
use the workflow component, you can now see a crazy cool visualization of your workflow
inside the profiler.

There are also some changes to the logout system - just to make life a bit simpler....
And some new constraints, like a `PasswordStrengthConstraint` and another
that prevents suspicious characters, like zero width space characters where someone
might try to create a username that *looks* like someone else's username.

If you're building an API, there's an excellent `debug:serializer` command to see
all the metadata for a class.

And finally, the new `Webhook` and `RemoteEvent` components, which deserve their
own tutorial. So we'll save that for another time.

These are just a few of my favorite features, but you can look at *everything* by
going to the "Living on the Edge" section of the blog and filtering by the version.
A good way to nerd out.

## The Autowire Attribute

But I *do* want to walk through a few new features together, starting with 
improvements to the autowiring system. These happened over the last several
version of Symfony and... they do a lot of things. But the overall effect is
that you'll probably never need to go into `services.yaml` again. 

Let me show you. In an old tutorial, I added this `bind` for an `$isDebug` argument.
The reason I did that lives in `src/Controller/VinylController.php`: I gave this
controller an `$isDebug` argument, which isn't autowiraable.

In `services.yaml`, remove the `bind`.

When we refresh, error It says:

> Hey you silly person: you have an `$isDebug` argument on a service but I have no
> idea what to pass to that.

Hence why we had the `bind`. Starting a few Symfony versions ago, we now have an
`Autowire` attribute. When we have an argument that can't be autowired, this is
your friend. Add it before the arg and define what you want. This can be a service,
an expression, an environment variable, a parameter, a kitten, whatever. We want a
param: `kernel.debug`.

Inside, `dump($this->isDebug)` to make sure it's working.

And... it is! Autowire is my new favorite attribute, but if you hold command or
control to open this class... then double click on the `Attribute` directory, we
see a whole list of cool, dependency-injection related attributes. `Exclude` is a
way to exclude a class from being auto-registered as a service. `Autoconfigure`
and `AutoconfigureTag` are both ways to configure *options* on your service. 
Put this above your class - or even above an interface - and the options will
automatically apply to this service or services that implement that interface.

There's also `AutowireIterator` and `AutowireLocator`. If you have a set of services
that implement a tag, you can use `AutowireIterator` to get those services passed
to you as an iterator, or `AutowireLocator` to get them passed to you as a locator,
basically an associative array of services.

## Trying AutowireIterator

For example, let's pretend that, in `VinylController`, we want to get an iterable
of *every* console command in our app. Say `private iterable $commands`. And to
prove this is working, foreach over `$this->commands` as `$command`... then dump
the object.

If we stopped now, we'd get the classic error that says:

> I have no idea what to pass for this `$commands` argument!

We want an iterable of every services that implement a specific tag. Grab those
with `#[AutowireIterator]`, then the tag we want: `console.command`.

And just like that, we got them! We see all 102 console commands in my app.
I know, it's a silly example, but isn't that cool?

Back in the controller, undo that.

Next up: let's talk about a few subtle, but powerful new ways to fetch request data
like query parameters and the request payload.

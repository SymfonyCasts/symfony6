# Bind Arguments Globally

In practice, you rarely need to do anything inside of `services.yaml`. Most of the
time, when you add an argument to the constructor of a service, it's autowireable.
So you add the argument with the type-hint... and *keep* coding!

But the `$isDebug` argument is *not* autowireable since it's not a service. And
*that* forced us to *completely* override the service and specify that one argument
with `bind`. It works but... that was... kind of a lot of typing to do for such
a small thing.

# Moving bind to \_defaults

So here's a *different* solution. Copy that `bind` key, delete the service entirely,
and up, under `_defaults`, paste.

When we move over and try this... the page *still* works! How cool is that? And,
it makes sense. This section will automatically register our `MixRepository` as a
service... and then anything under `_defaults` will be applied *to* that service.
So the end result is exactly what we had before.

I *love* doing this! It allows me to set up project-wide conventions. For example,
now that we have this, we could add an `$isDebug` argument to the constructor of
*any* service and it will instantly work.

## Binding with Type_hints

By the way, if you want, you can *also* include the *type* with the bind.

So this would now *only* work if we use the `bool` type-hint with the argument.
If we used `string` here, for example, Symfony would *not* try to pass in that value.

## The Autowire Attribute

So the global bind is *awesome*. But starting in Symfony 6.1, there's *another*
way to specify a non-autowireable argument. Comment out the global `bind`. I *do*
till like doing this, but let's try the new way.

If we refresh now, we get an error because Symfony doesn't know what to pass to
the `$isDebug` argument. To fix that, go into `MixRepository` and, above the argument
(or before the argument if you're not using multiple lines), add a PHP 8 attribute
called `Autowire()`. Normally, PHP 8 attributes will auto-complete, but this *isn't*
auto-completing for me. That's actually due to a bug in PhpStorm. To get around this,
I'm going to type out `Autowire()`... then go to the top and start adding the `use`
statement for `Autowire`, which *does* give us an option to auto-complete. Hit "tab"
and... *tah dah*! If you want to make them alphabetical, you can move it around.

You may also notice that it's underlined with a message:

> Attribute cannot be applied to a property [...]

Again, PhpStorm is a bit confused because this is both a property *and* an argument.

Anyway, go ahead and pass this an argument `%kernel.debug%`.

Refresh now and... got it! Pretty cool, right?

Next: most of the time when you autowire an argument like `HttpClientInterface`,
there's only *one* service in a container that implements that interface. But what
if there were *multiple* HTTP clients in our container? How could we choose the
one we want? It's time to talk about *named autowiring*.

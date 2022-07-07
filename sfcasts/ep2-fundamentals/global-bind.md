# Global Bind

In practice, you rarely need to do anything inside of `services.yaml`. Most of the time, when you need an argument in a service, it's autowireable. So you add the argument with the type hint and *keep* coding. But the `$isDebug` argument *isn't* autowireable since it's not a service, and that forced us to completely override the service and specify that one argument with `bind`. It works but... *lame*.

So here's a *different* solution. Copy that `bind` key, delete the service entirely, and up under `_defaults`, paste. When we move over and try this... the page *still* works. And that makes sense. If you think about it, this line down here is going to automatically register our `MixRepository` as a *service*, and then anything under `_default` will be applied to that service. So the end result is exactly what we had before. I love doing this! It allows me to set up project-wide conventions. And thanks to this, I can now add an `$isDebug` argument to the construction of any service in it will work.

By the way, if you want to, you can also include the type here. So this would now *only* work if we use the `bool` type-hint inside of our argument. If I used `string` here, for example, it *wouldn't* try to pass in that value.

Okay, the global bind is *awesome*, but starting in Symfony 6.1, there's *another* way to specify a non-autowireable argument. Comment out this global `bind`. I still like doing this, but let's try this new way.

If we refresh right now, we get an error because Symfony doesn't know what to pass to the `$isDebug` argument. To fix that, go into our `MixRepository` service and, above the argument (or before the argument if you're not using multiple lines), add a PHP 8 attribute called `Autowire()`. Normally, PHP 8 attributes will auto-complete, but this *isn't* auto-completing for me. That's actually due to a current bug in PhpStorm. To get around this, I'm going to type out `Autowire()` right here, then go to the top and start adding the `use` statement for `Autowire` which *does* give us an option to auto-complete. Hit "tab" and... *tah dah*! And if you want to make them alphabetical, you can just move this where you need it.

You may also notice that it's underlined, and if you hover over this, it says:

`Attribute cannot be applied to a property [...]`

PhpStorm is just *confused* because this is both a property *and* an argument to the method. Anyway, go ahead and pass this `%kernel.debug%`. Refresh now and... got it! Pretty cool, right?

Okay, most of the time, when you autowire an argument like `HttpClientInterface`, there's only one service in a container that implements that interface. But what if there were *multiple*? What if there were multiple HTTP clients in our container and we could *choose* the one we want? Let's talk about *named autowiring* next.

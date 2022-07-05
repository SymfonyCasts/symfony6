# Manual Service Config in services.yaml

At your terminal, run:

```terminal
bin/console debug:container --parameters
```

One of the `kernel` parameters is called `kernel.debug`. In addition to environments,
Symfony has this concept of "debug mode". It's *true* for the `dev` and *false* for
`prod`. And, occasionally, it comes in handy in your code.

So we have a new *challenge* (mostly to see if we can do it). Inside of
`MixRepository`, I want to figure out if we're in debug mode. If debug mode is *true*,
we will cache for *5 seconds*. If it's *false*, I want to cache for *60 seconds*

## Dependency Injection!

Okay, let's back up for a minute. Suppose you're working inside of a service like
`MixRepository`. Suddenly you realize that you need some *other* service like
the logger. What do you do to get the logger? The answer: you do the dependency
injection dance. You add a `private LoggerInterface $logger` argument and property...
then you use it down below in your code. You'll do this *tons* of times in Symfony.

Let me undo that... because we don't actually need the logger right now. But what
we *do* need to do is actually very similar. Right now we're inside of a service
and we've suddenly realized that we need some configuration (the `kernel.debug`
flag) to do our work. What do we do to get that config? The *same* thing: add that
as an argument to our constructor. I'll call it `private bool $isDebug`, and down
here, we can just say something like `$this->isDebug ? 5 : 60`.

So, if we *are* in debug mode, cache 5 seconds, else cache 60 seconds.

## Non-Autowireable Arguments

But... there's a slight complication... and I bet you already know what it is. When
we refresh the page... yikes! We get a `Cannot resolve argument` error. If you skip
a bit, it says:

> Cannot autowire service `App\Service\MixRepository`: argument `$isDebug` of
> method `__construct()` is type-hinted `bool`, you should configure its
> value explicitly.

That makes sense. Autowiring only works for services. You can't have a bool
`$isDebug` argument and expect Symfony to somehow realize that we want it to
pass us is this `kernel.debug` parameter. I might be a wizard, but I don't have a
spell for that. I *can* make a whole slice of pie disappear, though. With magic.
Definitely.

## Configuring MixRepository in services.yaml

So how do we fix this? Open a file that we haven't looked at yet:
`config/services.yaml`. So far, we haven't needed to add any configuration for our
`MixRepository` service. The container saw the `MixRepository` class as soon as we
created it... and autowiring helped the container know which arguments to pass to
the constructor. But now that we have a non-autowireable argument, we need to give
the container a *hint*. And we do that in this file.

Head down to the bottom and add the full namespace of this class:
`App\Service\MixRepository`. Below that, use the word `bind`. And below *that*,
we're give Symfony a *hint* to tell it what to pass to the argument by saying
`$isDebug` set to `%kernel.debug%`

I'm using `$isDebug` on purpose. That needs to *exactly* match the name of
the argument in the class so that Symfony knows to fill in *this* argument with
the `kernel.debug` parameter value.

Let's try it. And... it works! The two service arguments are still autowired, but
we filled in the one *missing* argument so that the container could create our
service. Nice!

I want to talk more about the purpose of this file and all of the configuration up
here. It turns out that a lot of the magic we've been seeing related to services
and autowiring can be explained by this code. That's *next*.

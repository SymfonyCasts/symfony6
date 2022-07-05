# Service Config

At your terminal, run:

```terminal
bin/console debug:container --parameters
```

One of the `kernel` parameters up here is called `kernel.debug`. In addition to environments, Symfony has this concept of "debug mode". It's *true* for dev and *false* for prod, and occasionally, it comes in handy in your code. So we have a new *challenge* (mostly just to see how to do it). Inside of `MixRepository.php`, I want to figure out if we're in debug mode. If debug mode is *true*, I want to cache for *5 seconds*, but if it's *false*, I want to cache for *60 seconds*.

Okay, let's back up for a second. Suppose you're working inside a service like `MixRepository`, and you suddenly realize you need to use some *other* service like the logger. What do you do to get the logger? The answer: You do the dependency injection dance. You add a `private LoggerInterface $logger` argument and property, and then you use it down below in your code. You'll do this *tons* of times in Symfony. Let me undo that because we don't actually need the logger right now. What we *do* need to do is actually very similar to that. We're inside of a service when we suddenly realize that we need some configuration (the `kernel.debug` flag) to do our work. So what do we do here? The *same* thing - add that as an argument to our constructor. I'll call it `private bool $isDebug`, and down here, we can just say something like `$this->isDebug ? 5 : 60`. This basically says:

`If we're in debug mode, cache this for 5 seconds.
Otherwise, cache it for 60 seconds.`

But there's a slight complication and I bet you already know what it is. If I refresh the page... yikes! I get a `Cannot resolve argument` error. If you skip over here, it says:

`Cannot autowire service "App\Service\MixRepository":
argument "$isDebug" of method "__construct()" is
type-hinted "bool", you should configure its
value explicitly.`

That makes sense. Autowiring only works for services. You can't have a bool `$isDebug` argument and expect Symfony to somehow realize that what we want it to pass us is this `kernel.debug` flag. I might be a wizard, but I don't have a spell for that. I *can* make a whole slice of pie disappear, though. With magic. Definitely.

So how do we fix this? Open up a new file we haven't looked at yet: `config/services.yaml`. So far, we haven't needed to add any configuration for our `MixRepository` service. The container saw this `MixRepository` class as soon as we created it, and autowiring helped the container know which arguments to pass to the constructor. But now that we have a non-autowireable argument, we need to give the container a *hint*. And we do that in this file. Head down to the bottom and add the full namespace of this class:`App\Service\MixRepository`. Below that, we're going to use the word `bind`. And below *that*, we're going to give Symfony a *hint* and tell it what to pass to this argument by saying `$isDebug` set to `%kernel.debug%`.

Quick reminder: I'm using `$isDebug`, and that needs to *exactly* match the name of the argument here, so it will tell Symfony which value to pass this argument. Then I'm setting this to the special "%[parameter name]%" syntax.

Let me try this now. It works! The two service arguments are still autowired, but then we filled in the one missing argument so that the container could create our service. Nice!

I want to talk more about the purpose of this file and all of the configuration up here. It turns out that a lot of the magic we've been seeing related to services can be explained by this code. That's *next*.

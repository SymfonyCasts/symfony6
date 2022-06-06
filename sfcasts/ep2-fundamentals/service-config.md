# Service Config

Coming soon...

Oh at your terminal run `bin/console debug:container --parameters`. Again, One of the
kernel parameters up here is called `kernel.debug` In addition to environments
Symfony has this concept of debug mode. It's true for dev and false for prod. And
occasionally it's a handy thing to be able to use in your code. So new challenge,
mostly just to see how to do it inside of MixRepository. I want to figure out if we
are in debug mode, If we, if debug mode is true, I want to cache for 5 seconds, but
if it's false, I want to cache for 60 seconds. So let's back up. Suppose you're
working inside a service like MixRepository, and you suddenly realize you need to use
some other service like the logger. What do you do to get the logger answer? You do
the dependency injection dance. You add a `private LoggerInterface $logger` argument,
uh, property, argument, and property. And then you use it down below in your code.
You'll do this tons of times in Symfony. Let me undo that cuz we don't actually need
the logger right now. What we need to do Is actually very similar to that where
inside of a service, and we suddenly realize that we need some configuration the
`kernel.debug` flag

In order to do our work. So What do we do the same thing? Add that as a constructor,
as an argument to our constructor. How about `private bool $isDebug`. And then down
here we can just say something like If `($this->isDebug)`, then use 5, otherwise use
60, But there's a slight complication and I bet you are going to guess it. If I
refresh the page, Yikes annot resolve argument. If you kinda skip here, it says
cannot Autowire service `MixRe pository` :argument `isDebug` of method `_Construct()`
is type-hinted `bool`, You should configure its value explicitly. So that makes
sense. Autowiring only works for services. You can't have a bool is debug argument.
And somehow magically expect Symfony to realize that what we want it to pass us is
this kernel, that debug flag, there is no such crazy black magic. So what do we do?
Open up a new file. We haven't looked at yet config `services.yaml` So far, we have
not needed to add any configuration For our MixRepository service. The container
magically saw the class, saw it as soon as we created the mix repository class and
autowiring has helped the container know

Which arguments to pass to the instructor. But now that we have a non autowire
argument, we need to give the container a hint. And we do that in this file. Head
down to the bottom And add the full namespace of this class
`App\Service\MixRepository:` And then below there, we're going to use a word `bind`
and below that we're going to hint. We're going to tell symfony what to pass to this
argument by saying `$isDebug: '%kernel.debug%`. So a quick reminder. So I'm using
`$isDebug` here. That needs to exactly match the name of the argument here. Tell
symfony which value to pass this argument. And then I'm setting this to the
parameter, the make the special percent parameter name, and then percent syntax. Let
me try it. Now. It works. The two service arguments are still autowired, but then we
filled in the one missing argument so that the container could create our service.
But I want to talk more about the purpose of this file and all this configuration up
here. It turns out that a lot of the magic we've been seeing related to services can
be explained by this code. That's next.


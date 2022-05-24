# Service Config

Coming soon...

Oh at your terminal run bin console, debug container-dash parameters. Again, One of
the kernel parameters up here is called kernel.debug. In addition to environments,
Symfony has this concept of debug mode. It's true for dev and false for prod. And
occasionally it's a handy thing to be able to use in your code. So new challenge,
mostly just to see how to do it inside of mixed repository. I want to figure out if
we are in debug mode, If we, if debug mode is true, I want to cache for five seconds,
but if it's false, I want to cache for 60 seconds. So let's back up. Suppose you're
working inside a service like mixed repository, and you suddenly realize you need to
use some other service like the logger. What do you do to get the logger answer? You
do the dependency injection dance. You add a private logger interface, logger
argument, uh, property, argument, and property. And then you use it down below in
your code. You'll do this tons of times in Symfony. Let me undo that cuz we don't
actually need the logger right now. What we need to do Is actually very similar to
that where inside of a service, and we suddenly realize that we need some
configuration, the kernel.debug flag

In order to do our work. So What do we do the same thing? Add that as a constructor,
as an argument to our constructor. How about private STR uh, bull is debug. And then
down here we can just say something like If this is debug, then use five, otherwise
use 60, But there's a slight complication and I bet you are going to guess it. If I
refresh the page, Yikes Cannot resolve argument. If you kinda skip here, it says
cannot auto wire service repository argument is debug of method. Construct is type
heed bull. You should configure its value explicitly. So that makes sense. Auto
wiring only works for services. You can't have a bull is debug argument. And somehow
magically expect Symfony to realize that what we want it to pass us is this kernel,
that debug flag, there is no such crazy black magic. So what do we do? Open up a new
file. We haven't looked at yet config services.YAML So far, we have not needed to add
any configuration For our mix repository service. The container magically saw the
class, saw it as soon as we created the mix repository class and auto wiring has
helped the container know

Which arguments to pass to the instructor. But now that we have a non auto wire
argument, we need to give the container a hint. And we do that in this file. Head
down to the bottom And add the full namespace of this class app /service /mix
repository, colon. And then below there, we're going to use a word bind and below
that we're going to hint. We're going to tell Symfony what to pass to this argument
by saying dollar sign is debug:one And then percent kernel.debug percent. So a quick
reminder. So I'm using is debug here. That needs to exactly match the name of the
argument here. Tell Symfony which value to pass this argument. And then I'm setting
this to the parameter, the ma the special percent parameter name, and then percent
syntax. Let me try it. Now. It works. The two service arguments are still on a wired,
but then we filled in the one missing argument so that the container could create our
service. But I want to talk more about the purpose of this file and all this
configuration up here. It turns out that a lot of the magic we've been seeing related
to services can be explained by this code. That's next.


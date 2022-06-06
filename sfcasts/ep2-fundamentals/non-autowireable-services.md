# Non Autowireable Services

Coming soon...

Run `php bin/console debug:container`, And I'll make it a little bit smaller so that
everything shows up on one line. As we know, this command shows all of these services
in our container And only a small number of these are Autowireable. We know that
because a services Autowireable only if it's ID, which is this over here is a class
or interface name. So at first, it might look like the twig service Is not
Autowireable after all its ID. Twig is definitely not a class or interface, But if
you scroll up to the top, let's see, yes, There's another service in the container
called whose ideas `twig\environment`, Which is an alias to the service `twig`. This
is the little trick that Symfony does to make services Autowireable. As long as we
type in hint an argument with `twig\environment`, we get the twig service. However,
most of these service in this list do not have an alias like that. And so most are
not Autowireable. And that's usually fine if a service isn't Autowireable, It's
probably because you won't ever need to use it, But let's pretend that we do want to
use one of these services. Check out this one here, it's called "twig.Command.debug"

This is actually the service that's behind the debug twig command. You're going to
open up another tab here. Remember earlier we were in `./bin/console debug:twig` It
shows us all the functions and filters. Well, that comes from this service. So as a
challenge, let's say if we can inject this service into our mix repository, Then
execute that command and dump out the result. Okay? First things first in
MixRepository, We just discovered that in order to do our work, we need access to
another service. What do we do? The answer dependency injection, which is a, Which is
that fancy word for adding another constructive argument and setting it onto a
property, which we can do all at once. Private `$twigDebugCommand`. If we stopped it
right now and refreshed, no surprise. We get an air. Symfony has no idea what to pass
for that argument Okay. So what if we added the type for this class back over in our
command, you can see that twig, this service is an instance of this debug command. So
over here, let's add a type to it `DebugCommand`. We want the one from Symfony bridge
twig

Hit tab to I complete that and then refresh Still an error. Okay. We should add the
type because we are good programmers, but no matter how hard we try, this is not an
autowireable service. So how do we fix this? There are two main ways. First I'll show
you the old way, which I'm mostly doing, just because you'll see this in
documentation and blog posts all over the place In config package in `config
services.yaml`. Exactly like we did earlier with the isDebug argument. We're going to
override our service entirely `app\service\MixRepository`, Add a `bind` key. And then
we're going to hint what to pass to the `$twigDebug argument`. Now the only tricky
thing here Is what value do we set? For example, if I go and copy the service ID,
`twig.command.debug` and paste that there that's not going to work. That's going to
literally paste pass us that string. You can see if you refresh, it says argument
four must be of type debug command string. Given We want to tell symfony that we want
to pass the service that has this ID and in these Yaml files, there's a special
syntax to do that. It is prefixing the service ID with the at symbol.

As soon as we do that, the fact that this doesn't explode means it's working, But I
want you to remove this. What's the new way of doing things by leveraging that same
fancy Autowire attribute. So say `[autowire]` And then here in as the argument,
instead of just passing at a string, we're going to say `(service:
'twig.command.debug')` I love that before we try this, let's actually use the
service. So down here in find all Head down to find all now calling executing a
service manually in your PHP code is totally possible, but it is a little weird. It's
a little weird, but it is super fun. We need to create this `$output =
BufferedOutput()` object, and then we can call it by saying
`$this->twigDebugCommand->run(new ArrayInput([])`' This is kind of faking the
arguments that we're passing to the method, Pass that empty flAJAX and then `output`.
And then whatever that command output is going to be set onto the output variable. So
let's just `dd($output)`. All right. Moment of truth, refresh and got it. How fun is
that?

All right, but now that we got this working, Let's comment out that silliness, But
I'll keep our `twigDebugCommand` injected just for reference. Here's the key
takeaway. Most arguments to services are autowireable. Yay. When you hit an argument
that is not autowireable use the autowire attribute to point to the value or service
you need. All right next. Remember when I told you that mixed Repository was the
first service we ever created? Yep. I lied. It turns out that our controllers have
been services this whole time.

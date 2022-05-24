# Non Autowireable Services

Coming soon...

Run bin console, Debug container, And I'll make it a little bit smaller so that
everything shows up on one line. As we know, this command shows all of these services
in our container And only a small number of these are auto wire. We know that because
a services auto wire only if it's ID, which is this over here is a class or interface
name. So at first it might look like the twig service Is not OWI after all it's ID.
Twig is definitely not a class or interface, But if you scroll up to the top, let's
see, yes, There's another service in the container called whose ideas, twig
/environment, Which is an alias to the service twig. This is the little trick that
Symfony does to make services auto wire. As long as we type in hint, an argument with
twig /environment, we get the twig service. However, most of these service in this
list do not have an alias like that. And so most are not OWI. And that's usually fine
if a service isn't auto wire, It's probably because you won't ever need to use it,
But let's pretend that we do want to use one of these services. Check out this one
here, it's called twig.command.debug.

This is actually the service that's behind the debug twig command. You're going to
open up another tab here. Remember earlier we were in debug:twig bin console. It
shows us all the functions and filters. Well that comes from this service. So as a
challenge, let's say, if we can inject this service into our mixed repository, Then
execute that command and dump out the result. Okay? First things first in mixed
repository, We just discovered that in order to do our work, we need access to
another service. What do we do? The answer dependency injection, which is a, Which is
that fancy word for adding another constructive argument and setting it onto a
property, which we can do all at once. Private Twig, debug command. If we stopped it
right now and refreshed, no surprise. We get an air. Symfony has no idea what to pass
for that <affirmative> Okay. So what if we added the type for this class back over in
our command, you can see that twig, this service is an instance of this debug
command. So over here, let's add a type to it. Debug command. We want the one from
Symfony bridge, twig

Hit tab to I complete that and then refresh Still an error. Okay. We should add the
type because we are good programmers, but no matter how hard we try, this is not an
auto wire service. So how do we fix this? There are two main ways. First I'll show
you the old way, which I'm mostly doing, just because you'll see this in
documentation and blog posts all over the place In config package, in config services
at YAML. Exactly like we did earlier with the is debug argument. We're going to
override our service entirely app /service /repository, Add a bind key. And then
we're going to hint what to pass to the twig debug argument. Now the only tricky
thing here Is what value do we set? For example, if I go and copy the service ID,
twig command, debug and paste that there that's not going to work. That's going to
literally paste pass us that string. You can see if you refresh, it says argument
four must be of type debug command string. Given We want to tell Symfony that we want
to pass the service that has this ID and in these Yamo files, there's a special
syntax to do that. It is prefixing the service ID with the at symbol.

As soon as we do that, the fact that this doesn't explode means it's working, But I
want you to remove this. What's the new way of doing things by leveraging that same
fancy auto wire attribute. So say auto wire And then here in as the argument, instead
of just passing at a string, we're going to say service and then twig.debug command.
I love that before we try this, let's actually use the service. So down here in find
all Head down to find all now calling executing a service manually in your PHP code
is totally possible, but it is a little weird. It's a little weird, but it is super
fun. We need to create this buffered output object, and then we can call it by saying
this->twig, Debu command,->run, and then pass it a new array input. This is kind of
faking the arguments that we're passing to the method, Pass that empty flAJAX and
then output. And then whatever that command output is going to be set onto the output
variable. So let's just DD Output <affirmative> output. All right. Moment of truth,
refresh and got it. How fun is that?

All right, but now that we got this working, Let's comment out that silliness, But
I'll keep our twig debug command injected just for Reference. Here's the key
takeaway. Most arguments to services are OWI. Yay. When you hit an argument that is
not OWI use the auto wire attribute To point to the value or service you need. All
right next. Remember when I told you that mixed repository was the first service we
ever created? Yep. I lied. It turns out that our controllers have been services this
whole time.


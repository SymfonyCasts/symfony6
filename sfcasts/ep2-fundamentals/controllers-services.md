# Controllers Services

Coming soon...

Open up a source of controller vinyl controller. It may or may not be obvious, but
our controller classes are also services In the container. Yep. The <affirmative>
yep. They feel special because they're controllers, but really they're just good old,
boring services, like everything else. Well, except that they have one superpower
that nobody else has the ability to auto wire arguments into its action methods.
Normally that only works with the constructor. One nice thing is that the action
methods Really do work just like the constructors.

That's all.

When it comes to auto wiring, For example, add a bull is debug Argument to our browse
action. And then let's dump as debug down here That will not work so far. The only
arguments that we know of that Symfony will pass us are wild cards in the route like
slug Or OWI service or auto variable services like mixed repository. But now go back
to config services,.YAML, and uncommon that global bind that we had earlier. And now
it works Okay. Going in the other direction Because controllers are services. You can
absolutely have a construction if you want. Let's move mixed repository and is debug
up to a new construction. This on top, we'll say public function Under score
construct. I'll pace those two arguments, Put them on their own lines. And then to
turn those into properties led private In front of each. Now down below, we just need
to use this->is debug and this->mix repository. And we're trying this, that works
just fine Though. I don't normally follow this approach mainly because adding
arguments to the action method is just so darn easy, but if you need a service or
argument in every action method in your class, you can absolutely clean up your
argument list by injecting it through the construction. All right, let's remove that
dump next. Let's talk about the super important concept of environment variables and
the purpose of the.end file that we were looking at earlier.


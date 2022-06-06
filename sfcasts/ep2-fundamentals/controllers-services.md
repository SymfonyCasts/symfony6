# Controllers Services

Coming soon...

Open up `src/Controller/VinylController.php`. It may or may not be obvious, but
our controller classes are also services In the container. Yep, They feel special
because they're controllers, but really they're just good old, boring services, like
everything else. Well, except that they have one superpower that nobody else has the
ability to Autowire arguments into its action methods. Normally that only works with
the constructor. One nice thing is that the action methods Really do work just like
the constructors.

When it comes to Autowire, For example, add a `bool isDebug` Argument to our browse
action. And then let's `dump ($isDebug)` down here That will not work so far. The
only arguments that we know of that symfony will pass us are wild cards in the route
like slug or Autowire services like mixed repository. But now go back to config
`services.Yaml`, and uncommon that global `bind` that we had earlier. And now it
works Okay. Going in the other direction Because controllers are services. You can
absolutely have a constructor if you want. Let's move mixed repository and isDebug up
to a new constructor. This on top, we'll say public `function__construct()`. A paste
those two arguments, Put them on their own lines. And then to turn those into
properties led `private` In front of each. Now down below, we just need to use
`($this->isDebug)` and `$this->MixRepository`. And we're trying this, that works just
fine Though. I don't normally follow this approach mainly because adding arguments to
the action method is just so darn easy, but if you need a service or argument in
every action method in your class, you can absolutely clean up your argument list by
injecting it through the constructor. All right, let's remove that dump next. Let's
talk about the super important concept of environment variables and the purpose of
the .env file that we were looking at earlier.

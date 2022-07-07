# Controllers Services

Open up `src/Controller/VinylController.php`. It *may* or may not be obvious, but our controller classes are *also* services in the container. Yep! They *feel* special because they're *controllers*, but they're really just good old, boring services like everything else. Well, except that they have one *superpower* that nothing else has: The ability to autowire arguments into its action methods. Normally, that only works with the constructor.

The action methods really *do* work just like the constructors when it comes to autowiring, and it's *super* nice. For example, add a `bool $isDebug` argument to our `browse()` action and then, down here, `dump($isDebug)`. That... didn't work. So far, the only arguments we know of that Symfony will pass us are wildcards in the route, like `$slug`, or autowire services like `MixRepository`. Go back to `config/services.yaml` and un-comment that global `bind` from earlier. And now... it works!

Going in the *other* direction, because controllers are services, you can *absolutely* have a constructor if you want. Let's move `MixRepository` and `$isDebug` up to a new constructor. I'll put his on top... and we'll say `public function __construct()`. Inside, paste those two arguments and put them on their own lines. And then, to turn those into properties, add `private` in front of each. Now, down here, we just need to make sure we change this to `dump($this->isDebug)` and add `$this->` in front of `MixRepository`. Nice! if we try this now... that works just fine.

I don't *normally* follow this approach, mainly because adding arguments to the action method is just so darn easy. But if you need a service or argument in *every* action method in your class, you can definitely clean up your argument list by injecting it through the constructor. I'll go remove that `dump()`.

Next, let's talk about the *super* important concept of environment variables and the purpose of the `.env` file that we were looking at earlier.

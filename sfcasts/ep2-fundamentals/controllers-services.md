# Controllers are Services Too!

Open up `src/Controller/VinylController.php`. It *may* or may not be obvious, but our
controller classes are *also* services in the container! Yep! They *feel* special
because they're *controllers*... but they're really just good old, boring services
like everything else. Well, except that they have one *superpower* that *nothing* else
has: the ability to autowire arguments into its action *methods*. Normally, autowiring
only works with the constructor.

## Binding Action Arguments

And, the action methods really *do* work *just* like the constructors when it comes
to autowiring. For example, add a `bool $isDebug` argument to the `browse()` action...
then `dump($isDebug)` below.

And that... doesn't work! So far, the only two things that we know we are allowed
to have as arguments to our "actions" are (A), the any wildcards in the route like
`$slug`, (B) autowireable services, like `MixRepository`.

But now, go back to `config/services.yaml` and *uncomment* that global `bind` from
earlier. And now... it works!

## Adding a Constructor

Going in the *other* direction, because controllers are services, you can *absolutely*
have a constructor if you want. Let's move `MixRepository` and `$isDebug` up to a
new constructor. Copy those, remove them... add `public function __construct()`,
paste.. then I'll put them on their own lines. To turn them into properties, add
`private` in front of each.

Back down below, we just need to make sure we change to `dump($this->isDebug)` and
add `$this->` in front of `mixRepository`.

Nice! If we try this now... that works just fine!

I don't *normally* follow this approach... mainly because adding arguments to the
action method is just so darn easy. But if you need a service or other value in
*every* action method of your class, you can definitely clean up your argument list
by injecting it through the constructor. I'll go remove that `dump()`.

Next, let's talk about the *super* important concept of environment variables and
the purpose of the `.env` file that we looked at earlier.

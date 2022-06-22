# Finding & Using the Services from a Bundle

We just installed KnpTimeBundle. Hooray! Um... but... uh... what does that mean?
What did doing that *give* us?

The *number* one thing that a bundle gives us is... services! What services does
*this* bundle give us? Well, we could, of course, read the documentation, blah,
blah. Well, ok, you *should* do that... but, come on! Let's venture ahead recklessly
and learn by exploring!

In the last tutorial, we learned about a command that shows us all of the services
in our app: `debug:autowiring`:

```terminal-silent
php bin/console debug:autowiring
```

For example, if we search for "logger", there's apparently a service called
`LoggerInterface`. We *also* learned that we can autowire any service in this list
into our controller by using its *type*. By using this `LoggerInterface` type -
which is actually `Psr\Log\LoggerInterface` - Symfony knows to pass us this service.
Then, down here, we call methods on it like `$logger->info()`.

We installed `KnpTimeBundle` a moment ago, so let's search for "time":

```terminal-silent
php bin/console debug:autowiring time
```

And... hey! Look at this! We have a new `DateTimeFormatter` service! That's from
the new bundle and I bet that's what we're looking for. Let's go use it in
our controller.

## Using the New DateTimeFormatter Service

The type-hint we need is `Knp\Bundle\TimeBundle\DateTimeFormatter`. Ok! In
`VinylController`, find `browse()`, then add the new argument.

By the way, the *order* of the arguments does *not* matter... except when it comes
to *optional* arguments. I made the `$slug` argument optional and you typically
need your optional arguments at the *end* of the list. So I'll add `DateTimeFormatter`
right here and hit "tab" to add the `use` statement on top.

We can *name* the argument anything we want, like `$sherlockHolmes` or
`$timeFormatter`:

[[[ code('616397799e') ]]]

To use this, loop over the mixes - `foreach ($mixes as $key => $mix)`: 

[[[ code('7da483ab67') ]]]

then, on each, add a new `ago` key: `$mixes[$key]['ago'] =`... and this is where we
need the new service. How do we *use* the `DateTimeFormatter`? I have no idea! But
we used its type, so PhpStorm should tell us what methods it has. Type
`$timeFormatter->`... and ok! It has 4 public methods.

The one *we* want is `formatDiff()`. Pass it the "from" time... which is
`$mix['createdAt']`:

[[[ code('85dac5f608') ]]]

That's *all* we need! We're looping over these `$mixes`, taking the `createdAt`
key, which is a `DateTime` object, passing it to the `formatDiff()` method, which
should return a string in the "ago" format. To see if this is working, below,
`dd($mixes)`:

[[[ code('ec67d50981') ]]]

Let's try it! Spin over, refresh... and let's open it up. Yes! Look at that: `"ago"
=> "7 months ago"`... `"ago" => "18 days ago"`... It *works*. So remove that dump:

[[[ code('07e24f0499') ]]]

And now that each mix has a new `ago` field, in `browse.html.twig`, replace the
`mix.createdAt|date` code with `mix.ago`:

[[[ code('47f88f8537') ]]]

And now... *much* better.

So: we had a problem... and *knew* it needed to be solved by a service... because
services do work. We didn't *have* a service that did what we needed *yet*, so we
went out, found one, and installed it. Problem *solved*! Symfony itself has a *ton*
of different packages, and each of them gives us several services. But sometimes
you'll need a third party bundle like this one to get the job done. Typically,
you can just search online for the problem you're trying to solve, plus "Symfony
bundle", to find it.

## Using the ago Twig Filter

In addition to the nice `DateTimeFormatter` service that we just used, this bundle
*also* gave us *another* service. But, this isn't a service that we're meant to
use directly, like in the controller. Nope! This service is meant to be
used by Twig *itself*... to power a brand new Twig filter! That's right! You can
add custom functions, filters... or *anything* to Twig.

To see the new filter, let's try another useful debugging command:

```terminal
php bin/console debug:twig
```

This prints a list of *all* of the functions, filters, and tests in Twig, along
with the *one* global Twig variable we have. If you go up to Filters, there's a new
one called "ago"! That was *not* there before we installed `KnpTimeBundle`.

So, all of the work we did in our controller is perfectly fine ... but it turns
out that there's an easier way to do all of this. Delete the `foreach`...
remove the `DateTimeFormatter` service... and, though it's optional, clean up
the extra `use` statement on top:

[[[ code('c1f3df9471') ]]]

In `browse.html.twig`, we don't have an `ago` field anymore... but we still have a
`createdAt` field. Instead of piping this into the `date` filter, pipe it to `ago`:

[[[ code('754b969c0e') ]]]

That's all we need! Back over on the site refresh and... we get the *exact*
same result.

By the way, we won't do it in this tutorial, but by the end, you'll be able to
easily follow the documentation to create your own custom Twig functions
and filters.

Ok, so our app does *not* have a database yet... and it won't until the *next*
episode. But to make things more interesting, let's get our mixes data by making
an HTTP call to a special GitHub repository. That's next.

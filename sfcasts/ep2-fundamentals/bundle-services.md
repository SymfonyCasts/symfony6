# Finding & Using the Services from a Bundle

We just installed KnpTimeBundle. Hooray! Um... but... um... what does that mean?
What did doing that *give* us.

The *number* one thing that a bundle gives you is... services! What services does
*this* bundle give us? Well, we could, of course, read the documentation, blah,
blah, and you *should* do that. But, come one! Let's venture ahead recklessly to
see if we can learn by exploring!

In the last tutorial, we learned about a command to show us all of the services
in our app: `debug:auotowiring`:

```terminal-silent
php bin/console debug:autowiring
```

For example, if we search for "logger", you can see there's a service called
`LoggerInterface`. In the first tutorial, we learned that we can autowire *any* of
the services in this list into our controller by using its type. By using this
`LoggerInterface` type - which is `Psr\Log\LoggerInterface` - Symfony knows
to pass us this service. Then, down here, we can call methods on it like
`$logger->info()`.

We installed `KnpTimeBundle` a moment ago, so let's search for "time":

```terminal-silent
php bin/console debug:autowiring time
```

And... hey! Look at this! We have new `DateTimeFormatter` service! That's from
the new bundle and I bet *that's* what we're looking for. Let's go use it in
our controller.

## Using the New DateTimeFormatter Service

The type-hint we need is `Knp\Bundle\TimeBundle\DateTimeFormatter`. Ok! In
`VinylController`, find `browse()`, then add the new argument.

By the way, the *order* of the arguments does *not* matter... except when it comes
to *optional* arguments. I made the `$slug` argument optional and you typically
need your optional arguments at the *end* of the list. So I'll add `DateTimeFormatter`
right here and hit "tab" to add the `use` statement on top.

We can *name* the argument anything we want. How about `$timeFormatter`.

to use this, loop over the mixes - `foreach ($mixes as $key => $mix)` - then, for
each mix, add a new `ago` key: `$mixes[$key]['ago'] =`... and this is where we
need the new service. How *do* we use the `DateTimeFormatter`? I have no idea! But
we used its type, so I bet PhpStorm will help us figure what methods this has.
Type `$timeFormatter->`... and you ok! It has 4 public methods.

The one *we* want is `formatDiff()`. Pass it the "from" time... which is
`$mix['createdAt']`.

And, that's *all* we need! So, we're looping over these `$mixes`, taking this
`createdAt` key, which is a `DateTime` object, *giving* it to the `formatDiff()`
function, which should return the string `ago` format. To see if this is working,
below, `dd($mixes)`.

Let's try it! Spin over, refresh... and let's open it up. Yes! Look at that: `"ago"
=> "7 months ago"`... `"ago" => "18 days ago"`... It *works*. Remove that dump.

Now that each mix has a new `ago` field, in `browse.html.twig`, we can remove the
`mix.createdAt|date('Y-m-d')` and replace it with `mix.ago`. And now... *much* better.

So: we had a problem, and *knew* it needed to be solved by a service... because
services do work. We didn't *have* a service that did what we needed *yet*, so we
went out, found one, and installed it. Problem *solved*! Symfony itself has a *ton*
of different packages, and each of them gives you several services. But sometimes
you'll need a third party bundle like this one to get the job done. And typically,
you can just search online for the problem you're trying to solve, plus "Symfony
bundle", to find it.

## Using the ago Twig Filter

In addition to the nice `DateTimeFormatter` service that we just used, this bundle
*also* gave us one *additional* service. But, this isn't a service that we're
meant to use directly, like in the controller. Nope! This service is meant to be
used by Twig *itself*... to power a brand new Twig filter! That's right! You can
add custom functions, filters... or *anything* to Twig.

To see the new filter, let's try another useful debugging command:

```terminal
php bin/console debug:twig
```

This gives you a list of *all* of the functions, filters, and tests in Twig, along
with the *one* global Twig variable we have. If you go up to Filters, there's a new
one called "ago"! That was *not* there before we installed `KnpTimeBundle`.

So, all of the work we did in our controller is perfectly fine ... but it turns
out there's an easier way to solve our problem. Delete the `foreach`...
remove the `DateTimeFormatter` service... and, though it's optional, I'll
clean up the extra `use` statement on top.

In `browse.html.twig`, we don't have an `ago` field anymore. But we still have a
`createdAt` field. Instead of piping this into the `date` filter, pipe it to `ago`.

That's all you need! Back over on the site, when we refresh... we get the *exact*
same result.

By the way, we won't do it in this tutorial, but by the end of this episode, you'll
be able to easily follow the documentation to create your own custom Twig functions
and filters.

Ok, so our app does *not* have a database yet... and it won't until the *next*
episode. But to make things more interesting, let's get our mixes data by making
an API call to a special GitHub repository.

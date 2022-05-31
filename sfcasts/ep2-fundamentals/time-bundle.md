# New Bundle, New Service: KnpTimeBundle

On our site, you can create your *own* vinyl mix. (Or you'll *eventually* be able
to do this... right now, this button doesn't actually do anything.). But another
great feature of our site is the ability to browser *other* user's mixes.

Now that I'm looking at this, it might be useful if we could see *when* each mix
was posted.

If you don't remember *where* in our code this page was built, you can use a trick.
Down on the web debug toolbar, if I hover over the 200 status code, this shows
me that the controller for this is `VinylController::browse`.

Cool! Go open up `src/Controller/VinylController.php`. *Here* is the `browse` action.
By the way, I've updated the code a *little* bit since episode one... so make sure
you've got a fresh copy if you're coding along with me.

This method calls `$this->getMixes()`... which is a private function I created down
at the bottom. This creates a *big* array of fake data that represents the mixes
we're going to render on the page. Eventually, we'll get this from a *dynamic* source,
like a database.

## Printing Dates in Twig

Anyway, notice that each mix has a `createdAt` date field. We get these mixes up
in `browse()`... and we pass them as the `mixes` variable to `vinyl/browse.html.twig`.
Let's jump into that template.

Down here, we use Twig's `for` loop to loop over `mixes`. Cool! Let's *also* print
the "created at" date. Add a `|`, another `<span>` and then say
`{{ mix.createdAt }}`.

There's just one problem. If you look at `createdAt`... it's a `DateTime` object.
And you can't simply *print* `DateTime` objects... you'll get a big error reminding
you of that.

Fortunately, Twig has a really nice `date` filter. We talked about filters briefly
in the first episode: they're used by adding a `|` after some value and then the
name of the filter. This *particular* filter takes an argument, which is the format
the date should be printed. To keep things simple, let's use `Y-m-d`, or
"year-month-day".

Head over and refresh and... okay! We can now see *when* these were posted, though
the format isn't very attractive. We *could* do more work here to spruce this up...
but it would be *way* cooler if we could print this out in the "ago" format.

You've probably seen it before.... like for comments on a blog post... they say
something like "posted three months ago" or "posted 10 minutes ago".

so... the question is: How *can* we convert a `DateTime` object into that nice `ago`
format? Well, that sounds like *work* to me and, as I said earlier, *work* in Symfony
is done by a service. So the *real* question is: Is there a *service* in Symfony
that can convert `DateTime` objects to the `ago` format? And the answer is... no.
But there *is* a third party bundle that can give us that service.

## Installing KnpTimeBundle

Go to https://github.com/KnpLabs/KnpTimeBundle. If you look at this bundle's
documentation, you'll see that this gives us a service that can do that conversion.
So... let's get it installed!

Scroll down to the `composer require` line, copy that, spin over to our terminal,
and paste.

```terminal-silent
composer require knplabs/knp-time-bundle
```

Cool! You can see that it grabbed `knplabs/knp-time-bundle`... and it *also*
installed `symfony/translation`, which is a dependency of `KnpTimeBundle`. Near
the bottom, it *also* configured two recipes. Let's see what those did. Run:


```terminal
git status
```

And... awesome! Any time you install a third party package, that will *always*
update your `composer.json` and `composer.lock` files. This *also* updated the
`config/bundles.php` file. That's because we just installed a bundle -
`KnpTimeBbundle` - and its recipe handled this automatically. It also looks like
the translation recipe added a config file and a `translations/` directory. We're
*not* going to worry about that stuff right now. The translator is needed to use
this bundle, but we won't have to worry about it at all.

So... what did installing this new bundle give us? Services of course! Let's
find and use those next!

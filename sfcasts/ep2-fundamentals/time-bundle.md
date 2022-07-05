# New Bundle, New Service: KnpTimeBundle

On our site, you can create your *own* vinyl mix. (Or you'll *eventually* be able
to do this... right now, this button doesn't do anything). But another
great feature of our site is the ability to browse *other* user's mixes.

Now that I'm looking at this, it might be useful if we could see *when* each mix
was created.

If you don't remember *where* in our code this page was built, you can use a trick.
Down on the web debug toolbar, hover over the 200 status code. Ah, ha! This shows
us that the controller behind this page is `VinylController::browse`.

Cool! Go open up `src/Controller/VinylController.php`. *Here* is the `browse` action:

[[[ code('1d9b35c7dc') ]]]

By the way, I *did* update the code a *little* bit since episode one... so make sure
you've got a fresh copy if you're coding along with me.

This method calls `$this->getMixes()`... which is a private function I created down
at the bottom:

[[[ code('12af057d07') ]]]

This returns a *big* array of fake data that represents the mixes
we're going to render on the page. Eventually, we'll get this from a *dynamic* source,
like a database.

## Printing Dates in Twig

Notice that each mix has a `createdAt` date field. We get these mixes up
in `browse()`... and pass them as a `mixes` variable into `vinyl/browse.html.twig`.
Let's jump into that template.

Down here, we use Twig's `for` loop to loop over `mixes`. Simple enough!

[[[ code('609c972403') ]]]

Let's *also* now print the "created at" date. Add a `|`, another `<span>` and then
say `{{ mix.createdAt }}`.

There's just one problem. If you look at `createdAt`... it's a `DateTime` object.
And you can't just *print* `DateTime` objects... you'll get a big error reminding
you... that you can't just print `DateTime` objects. Cruel world!

Fortunately, Twig has a handy `date` filter. We talked about filters briefly
in the first episode: we using them by adding a `|` after some value and then the
name of the filter. This *particular* filter also takes an argument, which is the
*format* the date should be printed. To keep things simple, let's use `Y-m-d`, or
"year-month-day".

[[[ code('cc122a1e01') ]]]

Head over and refresh and... okay! We can now see *when* each was created, though
the format isn't very attractive. We *could* do more work to spruce this up...
but it would be *way* cooler if we could print this out in the "ago" format.

You've probably seen it before.... like for comments on a blog post... they say
something like "posted three months ago" or "posted 10 minutes ago".

So... the question is: How *can* we convert a `DateTime` object into that nice
"ago" format? Well, that sounds like *work* to me and, as I said earlier, *work*
in Symfony is done by a service. So the *real* question is: Is there a *service*
in Symfony that can convert `DateTime` objects to the "ago" format? The answer
is... no. But there *is* a third party bundle that can give us that service.

## Installing KnpTimeBundle

Go to https://github.com/KnpLabs/KnpTimeBundle. If you look at this bundle's
documentation, you'll see that it gives us a service that can do that conversion.
So... let's get it installed!

Scroll to the `composer require` line, copy that, spin over to our terminal,
and paste.

```terminal-silent
composer require knplabs/knp-time-bundle
```

Cool! This grabbed `knplabs/knp-time-bundle`... as well as `symfony/translation`:
Symfony's translation component, which is a dependency of `KnpTimeBundle`. Near
the bottom, it *also* configured two recipes. Let's see what those did. Run:

```terminal
git status
```

Awesome! Any time you install a third party package, Composer will *always*
modify your `composer.json` and `composer.lock` files. This *also* updated the
`config/bundles.php` file:

[[[ code('35747b5619') ]]]

That's because we just installed a bundle - `KnpTimeBundle` - and its recipe 
handled that automatically. It also looks like the translation recipe added 
a config file and a `translations/` directory. The translator *is* needed 
to use KnpTimeBundle... but we won't need to work with it directly.

So... what did installing this new bundle give us? Services of course! Let's
find and use those next!

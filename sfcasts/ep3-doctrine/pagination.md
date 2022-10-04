# Pagination

Eventually, this page is going to get *super* long. By the time we have a thousand
mixes, it probably won't even load! We can fix this by adding *pagination*. Does
Doctrine have the ability to paginate results? It does! Though, I *usually* install
another library that adds more features on top of those from Doctrine.

Find your terminal and run:

```termninal
composer require babdev/pagerfanta-bundle pagerfanta/doctrine-orm-adapter
```

This installs a Pagerfanta bundle, which is a wrapper around a really nice library
called Pagerfanta. Pagerfanta can paginate lots of things, like Doctrine
results, results from Elasticsearch, and much more. We also installed its
Doctrine ORM *adapter*, which will give us everything we need to paginate our Doctrine
results. In this case, when we run

```terminal
git status
```

it added a *bundle*, but the recipe didn't need to do anything else. Cool! So how
does this library work?

Open up `src/Controller/VinylController` and find the `browse()` action. Instead
of querying for *all* of the mixes, like we're doing now, we're going to tell the
Pagerfanta library *which* page the user is currently on, how many results to show
*per* page, and then *it* will query for the correct results *for* us.

## Returning a QueryBuilder

To get this working, instead of calling `findAllOrderedByVotes()` and getting back
*all* of the results, we need to call a method on our repository that returns a
*QueryBuilder*. Open `src/Repository/VinylMixRepository` and scroll down to
`findAllOrderedByVotes()`. We're only using this method right here at the moment, so
rename it to `createOrderedByVotesQueryBuilder()`... and this will now
return a `QueryBuilder` - the one from Doctrine ORM. I'll remove the PHP
documentation on top... and the only thing we need to do down here is remove
`getQuery()` and `getResult()` so that we're *just* returning `$queryBuilder`.

[[[ code('6c1a403e0d') ]]]

Over in `VinylController`, change this to
`$queryBuilder = $mixRepository->createOrderedByVotesQueryBuilder($slug)`

[[[ code('46e927c5d9') ]]]

Initializing Pagerfanta is two lines. First, create the adapter -
`$adapter = new QueryAdapter()` and pass it `$queryBuilder`. Then create
the `Pagerfanta` object with
`$pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage()`

That's a *mouthful*. Pass this the `$adapter`, the current page - right now, I'm
going to hardcode `1` - and finally the max results per page that we want. Let's
use `9` since our mixes show up in three columns.

[[[ code('269af6c02f') ]]]

Now that we have this Pagerfanta object, we're going to pass *that* into the
*template* instead of `mixes`. Replace this with a new variable called `pager` set
to `$pagerfanta`.

[[[ code('e6c7cdde64') ]]]

The cool thing about this `$pagerfanta` object is that you can *loop* over it. And
as soon as you do, it will execute the correct query to get *just* this pages results.
In `templates/vinyl/browse.html.twig`, instead of `{% for mix in mixes %}`, say
`{% for mix in pager %}`.

[[[ code('27de4f1c48') ]]]

That's *it*. Each result in the loop will *still* be a `VinylMix` object.

If we go over and reload... got it! It shows *nine* results: the results for Page
1!

## Linking to the Next Page

What we need now are *links* to the next and previous pages... and this library can
help with that too. Back at your terminal, run:

```terminal
composer require pagerfanta/twig
```

One of the trickiest things about the Pagerfanta library is, instead of it being one
*giant* library that has everything you need, it's broken down into a bunch of
smaller libraries. So if you want the ORM adapter support, you need to install it
like we did earlier. If you want Twig support for adding links, you need to
install that too. Once you do though, it's pretty simple.

Back in our template, find the `{% endfor %}`, and right after, say
`{{ pagerfanta() }}`, passing it the `pager` object.

[[[ code('436293d547') ]]]

Check it out! When we refresh... we have links at the bottom! They're... ugly,
but we'll fix that in a minute.

## Reading the Current Page

If you click the "Next" link, up in our URL, we see `?page=2`. Though... the results
don't actually *change*. We're still seeing the same results from Page 1. And...
that makes sense. Remember, back in `VinylController`, I hardcoded the current page
to `1`. So even though we have `?page=2` up here, Pagerfanta *still* thinks we're
on Page 1.

What we need to do is *read* this query parameter and pass it as this second argument.
No problem! How do we read query parameters? Well, that's information from the
request, so we need the `Request` object.

Right before our optional argument, add a new `$request` argument type-hinted with
`Request`: the one from HttpFoundation. Now, down here, instead of `1`,
say `$request->query` (that's how you get query parameters), with
`->get('page')`... and default this to `1` if there is *no* `?page=` on the URL.

[[[ code('e22d104f10') ]]]

By the way, if you want, you can also add `{page}` up here. This way, Pagerfanta
will *automatically* put the page number inside the URL instead of setting it as
a query parameter.

If we head over and refresh... right now, we have `?page=2`. Down here... it knows
we're on Page 2! If we go to the next page... yes! We see a different set of results!

## Styling the Pagination Links

Though, this is *still* super ugly. Fortunately, the bundle *does* give us a way
to control the markup that's used for the pagination links. And it even comes
with automatic support for Bootstrap CSS-friendly markup. We just need to tell
the bundle to *use* that.

So... we need to configure the bundle. But... the bundle didn't give us any new
config files when it was installed. That's okay! Not all new bundles give us
config files. But as soon as you need one, create one! Since this bundle's called
`BabdevPagerfantaBundle`, I'm going to create a new file called
`babdev_pagerfanta.yaml`. As we learned in the last tutorial, the *name* of these
files *aren't* important. What's important is the root key, which should be
`babdev_pagerfanta`. To change how the pagination renders, add `default_view: twig`
and then `default_twig_template` set to
`@BabDevPagerfanta/twitter_bootstrap5.html.twig`.

[[[ code('80afb85b20') ]]]

Like any other config, there's no way you would know that this is the *correct*
configuration just by guessing. You need to check out the docs.

If we go back and refresh... huh, nothing changed. This is a little bug that you
sometimes run into in Symfony when you create a *new* configuration file.  Symfony
didn't *notice* it... and so it didn't know it needed to rebuild its cache. This
is a *super* rare situation, but if you ever think it might be happening, it's
easy enough to manually clear the cache by running:

```terminal
php bin/console cache:clear
```

And... oh... it *explodes*. You probably noticed why. I love this error!

> There is no extension able to load the configuration for "baberdev_pagerfanta"

It's supposed to be `babdev_pagerfanta`. Whoops! And now... perfect! It's happy. And
when we refresh... it sees it! In a real project, we'll probably want to add
some extra CSS to make this "dark mode"... but we've *got it*.

Okay team, we're basically done! As a bonus, we're going to refactor this pagination
into a JavaScript-powered *forever scroll*... except plot twist! We're going to do
that without writing a single line of JavaScript. That's *next*.

# Pagination

Eventually, this page is going to get *super* long. By the time we have a thousand mixes, it probably won't even load. We can fix this by adding *pagination*. Does Doctrine have the ability to paginate results? It does! Though, I *usually* install another library that adds even more features on top of it.

Find your terminal and run:

```termninal
composer require babdev/pagerfanta-bundle
pagerfanta/doctrine-orm-adapter
```

This installs a Pagerfanta bundle, which is a wrapper around a really nice library called Pagerfanta. Pagerfanta can paginate lots of things. We can paginate Doctrine ORM results, results from Elasticsearch, and so much more. We've also installed its Doctrine ORM adapter, which will give us everything we need to paginate our Doctrine results. In this case, when we run

```terminal
git status
```

it added a *bundle*, but the recipe didn't really do anything. So how does this work?

Open up `src/Controller/VinylController.php` and find the `browse()` action. Instead of querying for *all* of the mixes, like we're doing now, we're going to tell the Pagerfanta library which page the user is currently on, how many results to show per page, and then it will query for the correct set of results for that page.

To get this working, instead of calling `findAllOrderedByVotes()` and getting back *all* of the results, we need to call a method on our repository that returns a QueryBuilder. Open `src/Repository/VinylMixRepository.php` and scroll down to `findAllOrderedByVotes()`. We're only using this method right here at the moment, so I'm going to rename this to `createOrderedByVotesQueryBuilder()`. And this will now return a `QueryBuilder` - the one from Doctrine ORM. I'll remove the PHP documentation on top... and the only thing we need to do down here is remove `getQuery()` and `getResult()` so we're *just* returning `$queryBuilder`. Over in `VinylController.php`, we'll change this to

`$queryBuilder = $mixRepository->
createOrderedByVotesQueryBuilder($slug)`

Then, to initialize Pagerfanta to two lines, you're going to initialize a new adapter - `$adapter = new QueryAdapter()`. This is from Pagerfanta Doctrine ORM, so this is an adapter that works with Doctrine ORM. Then, we'll pass this the `$queryBuilder`. Finally, you'll create the Pagerfanta object with:

`$pagerfanta = Pagerfanta::
createForCurrentPageWithMaxPerPage()`

That's a *mouthful*. You can pass this whatever adapter you want, so pass it `$adapter`... and then the current page. Right now, I'm just going to hard code `1` and add the max results per page below that. I'll say `9` since our mixes show up in three columns.

Now that we have this Pagerfanta object, we're actually going to pass that into the *template* instead of `mixes`. So replace this with a new variable called `pager` set to `$pagerfanta`. The cool thing about this `$pagerfanta` object is that you can *loop* over it. And as soon as you do, it will create the correct query to get the results back. In `templates/vinyl/browse.html.twig`, instead of `{% for mix in mixes %}`, we'll say `{% for mix in pager %}`. That's *it*. It's still going to loop over the result of `VinylMix` objects. If we go over and reload... got it! It shows *nine* results - the results for Page 1.

What we need now are *links* to the next and previous pages, and this library can help with that too. Back at your terminal, run:

```terminal
composer require pagerfanta/twig
```

One of the trickiest things about the Pagerfanta library is, instead of it being one *giant* library that has everything you need, it's broken down into a bunch of smaller libraries. So if you want the ORM adapter support, you need to install it like we did earlier. If you want the Twig support for adding links, you need to install that. Once you do, though, it's pretty simple. Back in our template, find the `{% endfor %}`, and right after that, say `{{ pagerfanta() }}`, passing it the `pager` object.

Check this out! When we refresh... that adds links at the bottom! They're pretty ugly right now, but we'll fix that in a second. And if you click the "Next" link, up in our URL, you get `?page=2`. Though... the results don't actually *change*. We're still seeing the same exact results from Page 1, and... that makes sense. If you remember, back in `VinylController.php`, we hard coded the current page as page `1`. So even though we have `?page=2` up here, Pagerfanta still thinks we're on Page 1. What we need to do is *read* this query parameter and pass it as this second argument. No problem! How do we read query parameters? Well, that's information from the request, so we're going to want the `Request` object.

Right before our optional argument, add a new `Request` argument typehinted with `$request`. We want the one from HttpFoundation. Now, down here, instead of the `1`, we can say `$request->query` (that's how you get query parameters), with `->get('page')` defaulted to `1` if it's missing. By the way, if you want to, you can also add `{page}` up here. This way, Pagerfanta will *automatically* put the page number right there instead of setting it as a query parameter.

If we head over and refresh... right now, we have `?page=2`. Down here... it knows we're on Page 2. If we go to the next page... yes! You can see a different set of results! This is still super ugly, but the bundle *does* give you a way to control what the markup looks like when you call this function. And fortunately, it comes with a built-in way to generate markup that's Bootstrap CSS friendly. All we need to do is configure the bundle to tell it to use that markup.

We need to configure the bundle, but this bundle didn't give us any new configuration file. That's okay! Not all new bundles give you configuration files. As soon as you need one, you can create one. Since this bundle's called `babdev/pagerfanta`, I'm going to create a new file called `babdev_pagerfanta.yaml`. As we learned in the last tutorial, the name of these files aren't important. What's important is the root key, which should be `babdev_pagerfanta`. The way you change how the pagination renders is by adding `default_view: twig` and then `default_twig_template`. Here, you can say `@BabDevPagerfanta/Twitter_bootstrap5.html.twig`. Like any other configuration we've done, there's no way you would know that this is the *correct* configuration. You need to check out the documentation to be sure.

If we go back and refresh... nothing changed. This is a little bug that you can sometimes run into in Symfony when you create a new configuration file. Symfony didn't *see* this configuration file. If you modify an existing configuration file, it *will* see it. But in this case, it didn't see the new one, so it didn't know it needed to rebuild its cache. This is really the only case I can think of that this happens in Symfony, and it's likely a bug that will be fixed pretty soon.

In this case, to manually clear the cache, I'm going to run:

```terminal
./bin/console cache:clear
```

And... oh... it *explodes*. You probably noticed why. I love this error!

`There is no extension able to load the
configuration for "baberdev_pagerfanta"`

It's supposed to be `babdev_pagerfanta`. Whoops! And now... perfect! It's happy. And when we refresh... it sees it! In a real project, we'll probably still want to add some extra CSS to make this "dark mode", but we've *got it*.

Okay team, we are basically done! As a bonus, we're going to refactor this pagination into a JavaScript-powered *forever scroll*, except we're going to do that without writing even a single line of JavaScript. That's *next*.

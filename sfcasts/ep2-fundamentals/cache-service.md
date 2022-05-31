# Cache Service

When we refresh the `browse` page, the mixes you see here are currently coming from a HTTP request that we're making over to the GitHub API. We make a request to the GitHub API, which fetches this file right here, and then `$response->$toArray()` *decodes* the JSON into a `$mixes` array we can *then* render in a template. So this file up on GitHub is basically acting like our fake database.

One practical problem with this is that *every single* page load is now making a HTTP request, and HTTP requests can be *slow*. If I actually deployed this to production, our site would be so popular that we'd pretty quickly hit our GitHub API limit, and this page would *explode*. So... what if we cache the result instead? We could make this HTTP request, but then cache the result for 10 minutes, or even an hour. That just *might* work! We cache things in Symfony with - you guessed it - a service. But which one? I have no idea! Let's go find out.

Run:

```terminal
php bin/console debug:autowiring cache
```

This will search for services dealing with "cache". And... yes! There are, in fact, *several* inside of here. You can see here that there's something called `CacheItemPoolInterface`, and another called `StoreInterface`. Some of these services don't actually cache things, but the `CacheItemPoolInterface`, `CacheInterface`, and `TagAwareCacheInterface` are all different services that you can use for cacheing. They all effectively do the same thing, but he one you'll want to use is `CacheInterface`. So let's grab that.

Once again, we don't know *how* to use this service, but we can add another argument to our function called `CacheInterface` (make sure you get the one from `Symfony\Contracts\Cache`) and call it `$cache`. Then, to *implement* the `$cache` service, I'm going to copy these two lines from before, delete them, and then replace them with `$mixes = $cache->get()`, as if you're going to fetch the key out of the cache, and then we'll just make up some cache key here. How about `mixes_data`. Done!

Symfony's cache object works in a unique way. We're calling `$cache->get()` and then we pass it this key. If that result already exists in the cache, it will be returned *immediately*. If it *doesn't* exist in the cache yet, then it will call our *second* argument, which is a function. In here, our job is to return the data that *should* be cached. So I'm going to paste in those two lines of code that we copied earlier. This `$httpClient` is undefined, so I need to add `use ($httpClient)` up here to bring it into scope. There we go! And instead of setting the `$mixes` variable, we'll just `return` this `$response` line.

If you haven't used Symfony's cacheing service before, this might look a little strange at first, but don't worry. *This* is all that we need. The first time we refresh the page, there won't be any `mixes_data` in the cache yet. So it will call our function, return the result, and store *that* in the cache. The *next* time we refresh the page, that will already be in the cache, and this will return *immediately*. So we don't need "if" statements and we don't need to check for anything in the cache.

That might sound good in theory, but let's go see if it works. Move over, refresh and... beautiful! You can see that our first refresh still made the HTTP request like normal. And over here, you can see that there were *three* cache calls and *one* cache write. I'll click this to open it in a new tab and... this is really cool! This is the Cache section of the web debug toolbar, and here, you can see that there was one call to the cache for `mixes_data`, one cache *write*, and one cache *miss*. A cache "miss" means that it called our function and wrote that to the cache.

On the next refresh, watch this icon here. It disappears! That's because there was no HTTP request. If I open the Cache page again, you can see that there was one read and one hit. That hit was loaded from the cache and it *didn't* make a HTTP request. Nice!

You might be wondering how long this information is cached. Right now, it's *forever* because we didn't add an expiration. So to make this a little easier to use, let's add one. If you look at the cache documentation, you'll see that this function receives a `CacheItemInterface` argument (make sure to hit "tab" to add that use statement), and we'll call that `$cacheItem`. Then, down here, we can say `$cacheItem->expiresAfter()`, and to make it easy, I'll say `5` seconds. So this basically says that the item will store in cache for five seconds.

Unfortunately, if we try this, the item that's *already* in the cache is set to *never* expire. So this won't actually work until we clear the cache. Where is the cache being stored? Another great question! And we'll talk more about that in a second. But the cache, by default, is stored in the `/var/cache/dev` directory, along with a bunch of other cache files that help Symfony do its job. We *could* delete this directory manually to clear the cache, but Symfony has a better way to clear things. That, of course, is a `bin/console` command. Symfony has a bunch of different categories of cache called "cache pools". If you run

```terminal
php bin/console cache:pool:list
```

you'll get a list of them. Most of these are meant for Symfony to use, but the cache pool that we're using is called `cache.app` (no, it's not *that* "CashApp"). To clear that cache, you can run:

```terminal
php bin/console cache:pool:clear cache.app
```

That will clear our `cache.app` pool. This isn't something you'll need to do very often, but it's good to know how to do it, just in case.

Okay, check this out. When I refresh... I get a cache *miss* and you can see that it made an HTTP call. But if I refresh again really quick... it's gone! Refresh again and... it's back! That's because the five seconds just expired. Phew!

This is awesome! We have a service to make HTTP requests! And we have a service to cache stuff. The bundles handle preparing and creating those services for us with *zero* effort. We just toss them in our project and we're good to go!

But, I do have one question. What if we need to control these services? How could we tell the cache service that, instead of saving things into the file system in this directory, we want to store things in something like Redis? Let's explore the idea of *controlling* our services through configuration next.

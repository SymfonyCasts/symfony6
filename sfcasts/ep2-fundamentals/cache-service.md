# The Cache Service

*Now* when we refresh the browse page, the mixes are coming from a repository
on GitHub! We make an HTTP request to the GitHub API, that fetches this file right
here, we call `$response->toArray()` to *decode* that JSON into a `$mixes` array...
and then we render *that* in the template. Yup, this file on GitHub is our
temporary fake database!

One practical problem is that *every single* page load is now making an HTTP
request... and HTTP requests are *slow*. If we deployed this to production,
our site would be *so* popular, of course, that we'd pretty quickly hit our GitHub
API limit. And *then* this page would *explode*.

So... I'm thinking: what if we cache the result? We could make this HTTP request,
then cache the data for 10 minutes, or even an hour. That just *might* work!
How do we *cache* things in Symfony? You guessed it: with a service! Which service?
I dunno! So let's go find out.

## Finding the Cache Service

Run:

```terminal
php bin/console debug:autowiring cache
```

to search for services with "cache" in their name. And... yes! There are, in fact,
*several*! There's one called `CacheItemPoolInterface`, and another called
`StoreInterface`. Some of these aren't *exactly* what we're looking for, but
`CacheItemPoolInterface`, `CacheInterface`, and `TagAwareCacheInterface` *are* all
different services that you can use for caching. They all *effectively* do the same
thing... but the easiest to use is `CacheInterface`.

So let's grab that.... by doing our fancy autowiring trick! Add another argument
to our method typed with `CacheInterface` (make sure you get the one from
`Symfony\Contracts\Cache`) and call it, how about, `$cache`:

[[[ code('6b4b596a2d') ]]]

To *use* the `$cache` service, copy these two lines from before, delete them, 
and replace them with `$mixes = $cache->get()`, as if you're going to fetch 
some key out of the cache. We can invent whatever cache key we want: 
how about `mixes_data`.

Symfony's cache object works in a unique way. We call `$cache->get()` and pass
it this key. If that result already exists in the cache, it will be returned
*immediately*. If it does *not* exist in the cache yet, then it will call our *second*
argument, which is a function. In here, our job is to return the data that *should*
be cached. Paste in the two lines of code that we copied earlier. This `$httpClient`
is undefined, so we need to add `use ($httpClient)` to bring it into scope.

There we go! And instead of setting the `$mixes` variable, just `return` this
`$response->toArray()` line:

[[[ code('c6c78aea17') ]]]

If you haven't used Symfony's caching service before, this might look strange!
But I love it! The first time we refresh the page, there won't be any `mixes_data`
in the cache yet. So it will call our function, return the result, and then the
cache system will store *that* in the cache. The *next* time we refresh the page,
the key *will* be in the cache, and it will return the result *immediately*. So
we don't need any "if" statements to see if something is already in the cache...
just this!

## Debugging with the Cache Profiler

But... will it blend? Let's go find out. Refresh and... beautiful! The first refresh
*still* made the HTTP request like normal. Down on the web debug toolbar, we can
see that there were *three* cache calls and *one* cache write. Open this in a new
tab to jump into the cache section of the profiler.

So cool: this shows us that there was one call to the cache for `mixes_data`, one
cache *write*, and one cache *miss*. A cache "miss" means that it called our function
and wrote that to the cache.

On the next refresh, watch this icon here. It disappears! That's because there
was *no* HTTP request. If you open the Cache profiler again, this time there was
one read and one hit. That hit means that the result was loaded from the cache and
it did *not* make an HTTP request. That's exactly what we wanted!

## Setting the Cache Lifetime

Now, you *might* be wondering: how long will this info *stay* in the cache?
Right now... *forever*. Ooooh. That's the default.

To make it expire *sooner* than forever, give the function a `CacheItemInterface`
argument - make sure to hit "tab" to add that use statement - and call it
`$cacheItem`. Now we can say `$cacheItem->expiresAfter()` and, to make it easy,
say `5`:

[[[ code('205124d7cf') ]]]

The item will expire after 5 seconds.

## Clearing the Cache

Unfortunately, if we try this, the item that's *already* in the cache is set to
*never* expire. So... this won't actually work until we clear the cache. But...
where *is* the cache being stored? Another great question! We'll talk more about
that in a second... but, by default, it's stored in `var/cache/dev/`...
along with a bunch of other cache files that help Symfony do its job.

We *could* delete this directory manually to clear the cache... but Symfony has a
better way! It is, of course, another `bin/console` command.

Symfony has a bunch of different "categories" of cache called "cache pools". If
you run:

```terminal
php bin/console cache:pool:list
```

you'll see all of them. Most of these are meant for *Symfony* to use internally.
The cache pool that *we're* using is called `cache.app`. To clear that, run:

```terminal
php bin/console cache:pool:clear cache.app
```

Thats it! This isn't something you'll need to do very often, but it's good to know,
just in case.

Okay, check this out. When we refresh... we get a cache *miss* and you can see that
it *did* make an HTTP call. But if we refresh again really quickly... it's gone!
Refresh again and... it's back! That's because the five seconds just expired.

Ok team: we're now leveraging an HTTP client service *and* cache service... both
of which were prepared *for* us by one of our bundles so that we can just... use them!

But, I do have a question. What if we need to *control* these services? For example,
how could we tell the cache service that, instead of saving things onto the filesystem
in this directory, we want to store things in Redis... or memcache? Let's explore
the idea of *controlling* our services through configuration next.

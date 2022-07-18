# Configuring the Cache Service

So... I want to know how I can configure the cache service... like to store the
cache somewhere else. In the real world, we can just search for "How do I configure
Symfony's cache service". But... we can *also* figure this out on our own, by
using the commands we just learned.

We already noticed there's a `cache.yaml` file:

[[[ code('669c75d7a9') ]]]

It looks like FrameworkBundle is responsible for creating the cache service... 
and it has a sub `cache` key where we can pass *some* values to control it. 
All of this is commented-out at the moment.

To get more information about FrameworkBundle, run:

```terminal
php bin/console config:dump framework
```

FrameworkBundle is the main bundle inside of Symfony. So you can see that this
dumps... wow... a *ton*. FrameworkBundle provides a *lot* of services... so there's
a lot of config.

## Debugging the Cache Config

To... zoom in a bit, re-run the command again, passing `framework` *and* then
`cache` to filter for that sub-key:

```terminal-silent
php bin/console config:dump framework cache
```

And... cool! This may not always be *super* understandable, but it's a great starting
point. This definitely just helped us answer the question:

> Why does the cache system store stuff in the var/cache directory?

Because... there's a `directory` key that defaults to `%kernel.cache_dir%`... which
is a fancy way of pointing at the `/var/cache/dev` directory. And then we see
`/pools/app`, which is the actual directory that holds our cache.

## Using dump() and the Profiler

So here's the goal: instead of caching things to the filesystem, I want to change
the cache system to store somewhere else. Before we do that, go into
`VinylController` and, so we can see the *result* of the change we're about to make,
`dump($cache)`. We've been using `dd()` so far, which stands for "dump and die".
But in this case I want `dump()`... but let the page load.

[[[ code('8e64b535c2') ]]]

Refresh now. Wait, where *is* my dump? This is a... feature! When you use `dump()`,
you won't actually see it on the page: it hides down here on the web debug toolbar.
If you look there, the cache is some sort of `TraceableAdapter`. But inside of *that*,
there's an object called `FilesystemAdapter`. That's proof that the cache system
is *saving* to the filesystem.

## Configuring the Cache Adapter

To make this store somewhere else, go into `cache.yaml` and change this `app` key.
You can set this to a number of different special strings, called adapters. If we
wanted to store our cache in Redis, we would use `cache.adapter.redis`.

To make things really easy, use `cache.adapter.array`:

[[[ code('cc8987c4ae') ]]]

The `array` adapter is a *fake* cache where it *does* store things... but it only lives 
for the duration of the request. So, at the end of each request, it forgets 
everything. It's a fake cache, but it's enough to see how changing this key will affect 
the cache service itself.

Watch what happens. Currently, we have a `FilesystemAdapter`. When we refresh...
the cache is an `ArrayAdapter`! And since the `ArrayAdapter` forgets its cache at
the end of the request, you can see that every single request *does* now makes an
HTTP request.

## Takeaway: It's all about Controlling how Services are Instantiated

If you're a little confused by this, let me try to clear things up. The point of
this chapter is *not* to teach you how to change this *specific* key in the cache
file. Ultimately, if you need to configure something in Symfony, you'll just search
the docs... which will tell you exactly what to do and which key to change.

Nope, the big takeaway is that the *sole* purpose of these config files is to
*configure* the *services* in our app. Each time you change a key in *any*
of these files, the end result is that you just changed how some service is
*instantiated*. Tweaking a key may change the entire class name of a service object,
like in this case, or it may change the 2nd or 3rd constructor argument that
will be passed when the service is instantiated. It doesn't really matter
*what* changes, as long as you realize that this config is *all* about services
and how they're instantiated.

In fact, *none* of this config can be read directly from your app. You couldn't,
for example, ask for the "cache" configuration from inside of a controller. Nope,
Symfony reads this config, uses it to configure how each service object will be
instantiated, then throws it away. Services are supreme.

Next, sometimes you'll need certain configuration to be *different* based on whether
you're developing locally or running on production. Symfony has a system for
this called "environments". Let's learn *all* about that.

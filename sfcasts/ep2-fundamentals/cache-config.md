# Cache Config

We're now using the `HttpClientInterface` and `CacheInterface` services. Yay! *But* we aren't actually responsible for *creating* these services. They're created by something else (we'll talk about that in a few minutes), and then they're just passed to us. This is great because we can just use them without thinking too hard about it. They come ready to use, right out of the box. But if something else is responsible for *instantiating* these service objects, how can we control them? For example, I mentioned that the `$cache` object stores its files in the `/var/cache/dev` directory. How could we tell the cache service to cache somewhere else? *Introducing* bundle configuration!

Go check out the `/config/packages` directory. This has a number of different .yaml files in it, all of which are loaded *automatically* by Symfony when it first boots up. And the entire purpose of each of these files is to configure these services in the system. Open up `twig.yaml`. For now, you can ignore this `when@test`. We're going to talk about that in a few minutes. This file has a root key called `twig`, and the entire purpose of this file is to control Twig bundle. And it's not the file name, `twig.yaml` - that's important. I could rename this to "ryanlovespizza.yaml", and it would work *exactly* the same. The key is that when Symfony loads this file, it sees this root key, `twig`, and it says:

`Oh, okay. I'm going to pass whatever
configuration is below this to Twig bundle.`

Now, remember: Bundles give us *services*. Thanks to this config, when Twig bundle is preparing these services for us, Symfony passes us this configuration, and Twig bundle uses that to help instantiate its services. If we change this default path to something like `%kernel.project_dir%/views`, the result is that the Twig service that renders templates would now be preconfigured to look in this new directory. These files are meant for controlling services that the different bundles give us. And *every* single one of these files controls a different service. This `framework.yaml`, for example, if you look at root key `framework`, is all configuration that is passed to FrameworkBundle and helps configure its services.

As I mentioned, the file names don't matter. They often match the root keym like `framework` and `framework.yaml`, but not *always*. There's also `cache.yaml`, which is actually just more configuration for FrameworkBundle. We just happened to put it in its own file so it's easier to configure the cache system.

One question you might be thinking is: "What configuration can you put under this?" Because you can't just make up keys. That would give you an error inside of Symfony. Well, this is one of my favorite things about Symfony's configuration system. If you want to know what configuration you can pass to Twig bundle, there are two `bin/console` commands to help you. The first is

```terminal
php bin/console debug:config twig
```

where we are passing the route key, `twig`. This is going to print out all of your current configuration under the `twig` key, including any default values that the bundle's adding. You can see our `default_path` here, which is set to our `/templates` directory. That's coming from our configuration file. This `%kernel.project_dir%` is just a fancy way to point at the root of our project. Watch this. If I change this to `views`, and re-run that command, you'll see that this changes to `views`. Let me go ahead and change that back.

So `debug:config` is going to show us all of the configuration we can have under the `twig` key, plus whatever the current defaults are. So it's a great way to see what you can configure about a bundle. Normally, you would just read the documentation to find out how to configure stuff. But just by looking at this, we know we could likely set some global variables in Twig by using the `globals` key. Pretty cool stuff!

There's another command that's very similar to this. Instead of `debug:config`, it's `config:dump`. The `debug:config` command shows you your *current* configuration, but `config:dump` shows you a giant tree of *example* configuration, which includes *everything* that's possible. Here you can see `globals` and it even has some examples of global values that you could configure. So this is a great way to see every potential option that you can pass to a bundle to help Twig bundle configure its services.

So... I want to know how I can configure the cache service. In the real world, you can just Google "How do I configure Symfony's cache service?" but we can also just get help from these commands.

We already noticed there's a `cache.yaml` service. It looks like FrameworkBundle is responsible for creating the cache service. And it has a sub `cache` key, which we can pass different values to, to help control it. All of this is commented out at the moment.

To get more information about FrameworkBundle, let's run:

```terminal
php bin/console config:dump framework
```

FrameworkBundle is the main bundle inside of Symfony. So you can see that this dumps... wow... a *ton* of different things you can use to configure lots of different services in FrameworkBundle gives you. I'll zoom in a little bit, then let's actually pass at the argument's `framework`, and then `cache`. That will show us the `cache` sub key under `framework`. And... cool! This may not always be super understandable, but it's a great starting point. It helps us answer questions like "Why does the cache system store into the cache directory?" If we look down here, we can see that's because there's a `directory` key that defaults to `%kernel.cache_dir%`. That's a fancy way of pointing at the `/var/cache/dev` directory. And then we see `/pools/app`, which is actually the directory that's holding our cache right now. It has a number of other different configuration options below as well.

All right, here's my goal. Instead of cacheing things to the file system, I want to change the cache system to cache somewhere else. Before we do that, go into `VinylController.php` and, just so we can see what change this is going to make, I'm going to `dump($cache)`. We've been using `dd` so far, which stands for "dump and die". But `dump` just dumps the variable without killing the page. Check this out. I'll refresh... and when you use dump, you won't actually see it on the page. It's hiding down here on the web debug toolbar. But if you look here, there's some sort of `TraceableAdapter`. And inside of *that*, there's something called a `FilesystemAdapter`. That's the cache system *saving* to the filesystem.

If we wanted to have that cache somewhere else, we can go into `cache.yaml` and change this `app` key. You can figure this out by looking at the documentation, and it would help guide you through this. Then, over here, we have a number of different adapters that we can use. So if we wanted to store our cache in Redis, we we'd use `cache.adapter.redis`. Just to make things really easy, I'm going to use `cache.adapter.array`. The `array` is a fake cache where we can cache stuff during the request. But at the *end* of the request, it forgets it. So... it's *kind of* a fake cache, but it's enough to see that changing this key here will change the cache service itself. Watch what happens. Currently, we have a filesystem adapter. When I refresh... the cache is an `ArrayAdapter`! And since the `ArrayAdapter` forgets its cache at the end of a request, you can see that every single request now makes a HTTP request.

If you're a little confused by this, let me try to clear things up. The point of this chapter was *not* to teach you how to change this specific key in the cache file. Ultimately, if you need to configure something in Symfony, you'll just search for the documentation, which will tell you exactly what to do, such as which key you need to change. The big takeaway here is that the *sole* purpose of these files is to *configure* these services in our application. Any time you change a key in any of these files, the end result it a change to one of these services. It's still *all* about the services.

Next, sometimes you want some configuration to be different based on whether you're developing locally versus running on production. Symfony has a system for this called "environments". Let's learn more about that.

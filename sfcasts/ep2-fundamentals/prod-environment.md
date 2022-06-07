# Prod Environment

Our app is currently running in the dev environment. Let's try to switch to the prod environment, which is what you would use on production. Temporarily change this `APP_ENV=dev` to `prod`, then head over and refresh. Whoa! The web debug toolbar is *gone*. That's because the entire web profiler bundle is not enabled in the prod environment. You'll also notice that the dump we have from our controller is dumping on the top of the page. That's because the web profiler normally captures that and displays it down on the web debug toolbar. Since the debug toolbar isn't there anymore, it now dumps the information up here. And there are a lot of other differences. We know the logger will now behave differently, thanks to the configuration in `monolog.yaml`.

The way pages are built has also changed. For example, Symfony caches a lot of files, but you don't notice that in the dev environment because it's super smart and rebuilds its internal cache when we change certain files. However, in the prod environment, that doesn't happen. Check this out! Open up `templates/base.html.twig`... and change the title on the page to `Stirred Vinyl`. If you go back over and refresh... look up here! *No change*. The Twig templates themselves are *cached*. Again, in the dev environment, it rebuilds the cache *for* you. But in the prod environment, it *doesn't*. So how do you clear the cache in the prod environment?

At your terminal, run:

```terminal
php bin/console cache:clear
```

Notice it says it's clearing the cache for the *prod* environment. So, just like how our application always runs in a specific environment, the console commands run in a specific environment too. And, by default, it reads that same `APP_ENV` flag. So because we have `APP_ENV=prod` here, `cache:clear` knew that it should run in the *prod* environment and it cleared the cache for that environment. Thanks to this, when we refresh... *now* the template was updated. I'll change this back to our *cool* name, `Mixed Vinyl`.

All right, let's try something else! Right now, if we open up `config/packages/cache.yaml`, our cache service is using the `ArrayAdapter`, which is a fake cache. It doesn't *really* cache anything. That's cool for development, but it won't work on production. So let's see if we can switch that back to the filesystem adapter, but only for the prod environment. How? Down here, we can use `when@prod`, and then we'll just repeat the same keys we have here. So `framework`, `cache`, and then `app`. Finally, we'll add the adapter we want, which is called `cache.adapter.filesystem`.

It's going to be really easy to see if this works because we're still dumping the cache service in our controller. Right now, you can see it's an `ArrayAdapter`. If I refresh... surprise! It's *still* an `ArrayAdapter`. Why? Because we're in the *prod* environment, and pretty much any time you make a change in the prod environment, you need to rebuild your cache.

Go back to your terminal and run

```terminal
php bin console cache:clear
```

again and... got it - `FilesystemAdapter`! But I'm actually going to reverse this config here. I'll copy `cache.adapter.array` and I'm going to change it to `filesystem`. We'll use that by default, then at the bottom, I'll change this to `when@dev`, and *this* to `cache.adapter.array`. Why am I doing that? Well, that literally makes zero difference in the dev and prod environments. *But* if we decide to start writing tests later, which run in the *test* environment, with this new config, the test environment will use the same cache service as production (which is probably more realistic and better for testing).

To make sure this still works, let's clear the cache one more time. Refresh and... it does! We still have the `FilesystemAdapter`. And now, if we switch back to the `dev` environment in `.env` and refresh... yes! The web debug toolbar is back, and down here, we are once again using the `ArrayAdapter` in the dev environment. Perfect! In reality, you probably won't ever switch to the prod environment locally. It's just hard to work with. The prod environment is really meant for production.

All right, before we go on, I also want to go into `VinylController.php`, down in `browse()`, and let's take out that `dump`. Okay, status check! First, *everything* in Symfony is done by a service. Second, bundles *give* us services. And third, we can control *how* those services are instantiated via the different bundle configuration in `config/packages/`. Next, let's go one important step further by creating our own service.

# The "prod" Environment

Our app is currently running in the `dev` environment. Let's try to switch to `prod`...
which is what you would use on production. Temporarily change `APP_ENV=dev` to `prod`...
then head over and refresh. Whoa! The web debug toolbar is *gone*. That's because
the entire web profiler bundle is *not* enabled in the `prod` environment.

You'll also notice that the dump we have in our controller is dumping on the *top*
of the page. The web profiler normally *captures* that and displays it down on the
web debug toolbar. But... since the debug toolbar isn't there anymore, it now dumps
right when you call it.

And there are a lot of other differences, like the logger, which is now behaving
differently thanks to the configuration in `monolog.yaml`.

## Clearing the prod Cache

The way pages are built has also changed. For example, Symfony caches a lot of files...
but you don't notice that in the `dev` environment. That's because Symfony is super
smart and rebuilds that cache when we change certain files. *However*, in the
`prod` environment, that doesn't happen.

Check this out! Open up `templates/base.html.twig`... and change the title on the
page to `Stirred Vinyl`. If you go back over and refresh... look up here! *No change*!
The Twig templates themselves are *cached*. In the `dev` environment, Symfony rebuilds
the cache *for* you. But in the `prod` environment? Nope! You need to clear the
cache manually.

How? At your terminal, run:

```terminal
php bin/console cache:clear
```

Notice it says that it's clearing the cache for the *prod* environment. So, just
like how our app always runs in a specific environment, the console commands
*also* run in a specific environment. And, it reads that same `APP_ENV` flag. So
because we have `APP_ENV=prod` here, `cache:clear` knew that it should run in the
*prod* environment and cleared the cache for *that* environment.

Thanks to this, when we refresh... *now* the title changes. I'll change this
back to our *cool* name, `Mixed Vinyl`.

## Changing the Cache Adapter for prod Only

Ok, let's try something else! Open up `config/packages/cache.yaml`. Our cache service
currently uses the `ArrayAdapter`, which is a fake cache. That might be cool
for development, but it won't work for production.

Let's see if we can switch that back to the filesystem adapter, but *only* for the
`prod` environment. How? Down here, use `when@prod` and then repeat the same keys.
So `framework`, `cache`, and then `app`. Set this to the adapter we want which is
called `cache.adapter.filesystem`.

It's going to be *really* easy to see if this works because we're still dumping the
cache service in our controller. Right now, it's an `ArrayAdapter`. If we refresh...
surprise! It's *still* an `ArrayAdapter`. Why? Because we're in the *prod*
environment... and pretty much any time you make a change in the `prod` environment,
you need to rebuild your cache.

Go back to your terminal and run

```terminal
php bin console cache:clear
```

again and now... got it - `FilesystemAdapter`!

But... I'm want to actually *reverse* this config. Copy `cache.adapter.array` and
change it to `filesystem`. We'll use *that* by default. Then at the bottom, change
to `when@dev`, and *this* to `cache.adapter.array`.

Why am I doing that? Well, that literally makes zero difference in the `dev` and
`prod` environments. *But* if we decide to start writing tests later, which run in
the *test* environment, with this new config, the test environment will use the same
cache service as production... which is probably more realistic and better for testing.

To make sure this still works, let's clear the cache one more time. Refresh and... it
does! We still have the `FilesystemAdapter`. And now, if we switch back to the `dev`
environment in `.env`... and refresh... yes! The web debug toolbar is back, and down
here, we are once again using the `ArrayAdapter`!

Now, in reality, you probably won't ever switch to the `prod` environment while
you're developing locally. It's just hard to work with... and there's no point!
The `prod` environment is really meant for production! And so, you *will* run
that `bin/console cache:clear` command during deployment... but probably never
on your local machine.

Before we go on, head into `VinylController.php`, go down to `browse()`, and take
out that `dump`.

Okay, status check! First, *everything* in Symfony is done by a service. Second,
bundles *give* us services. And third, we can control *how* those services are
instantiated via the different bundle configuration in `config/packages/`.

Next, let's go one important step further by creating our *own* service.

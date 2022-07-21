# The "prod" Environment

Our app is currently running in the `dev` environment. Let's switch it to `prod`...
which is what you would use on production. Temporarily change `APP_ENV=dev` to
`prod`:

[[[ code('0ad4cd6444') ]]]

then head over and refresh. Whoa! The web debug toolbar is *gone*.
That... makes sense! The entire web profiler bundle is *not* enabled in the `prod`
environment.

You'll also notice that the dump from our controller appears on the *top*
of the page. The web profiler normally *captures* that and displays it down on the
web debug toolbar. But... since that whole system isn't enabled anymore, it now dumps
right where you call it.

And there are a lot of other differences, like the logger, which now behaves
differently thanks to the configuration in `monolog.yaml`.

## Clearing the prod Cache

The way pages are built has also changed. For example, Symfony caches a lot of files...
but you don't notice that in the `dev` environment. That's because Symfony is super
smart and rebuilds that cache automatically when we change certain files. *However*,
in the `prod` environment, that doesn't happen.

Check it out! Open up `templates/base.html.twig`... and change the title on the
page to `Stirred Vinyl`. If you go back over and refresh... look up here! *No change*!
The Twig templates *themselves* are cached. In the `dev` environment, Symfony rebuilds
that cache *for* us. But in the `prod` environment? Nope! We need to clear it
manually.

How? At your terminal, run:

```terminal
php bin/console cache:clear
```

Notice it says that it's clearing the cache for the *prod* environment. So, just
like how our *app* always runs in a specific environment, the console commands
*also* run in a specific environment. And, it reads that same `APP_ENV` flag. So
because we have `APP_ENV=prod` here, `cache:clear` knew that it should run in the
*prod* environment and clear the cache for *that* environment.

Thanks to this, when we refresh... *now* the title updates. I'll change this
back to our *cool* name, `Mixed Vinyl`.

## Changing the Cache Adapter for prod Only

Let's try something else! Open up `config/packages/cache.yaml`. Our cache service
currently uses the `ArrayAdapter`, which is a fake cache. That might be cool
for development, but it won't be much help on production:

[[[ code('8b4dd40fad') ]]]

Let's see if we can switch that back to the filesystem adapter, but *only* for the
`prod` environment. How? Down here, use `when@prod` and then repeat the same keys.
So `framework`, `cache`, and then `app`. Set this to the adapter we want, which is
called `cache.adapter.filesystem`:

[[[ code('38e1943114') ]]]

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

But... let's *reverse* this config. Copy `cache.adapter.array` and change it to
`filesystem`. We'll use *that* by default. Then at the bottom, change to `when@dev`,
and *this* to `cache.adapter.array`:

[[[ code('9554ab0ea8') ]]]

Why am I doing that? Well, that literally makes zero difference in the `dev` and
`prod` environments. *But* if we decide to start writing tests later, which run in
the *test* environment, with this new config, the test environment will use the same
cache service as production... which is probably more realistic and better for testing.

To make sure this still works, clear the cache one more time. Refresh and... it
does! We still have `FilesystemAdapter`. And... if we switch back to the `dev`
environment in `.env`:

[[[ code('02a3fa35f7') ]]]

and refresh... yes! The web debug toolbar is back, and down
here, we are once again using `ArrayAdapter`!

Now, in reality, you probably won't ever switch to the `prod` environment while
you're developing locally. It's hard to work with... and there's just no point!
The `prod` environment is really meant for production! And so, you *will* run
that `bin/console cache:clear` command during deployment... but probably almost
never on your local machine.

Before we go on, head into `VinylController`, go down to `browse()`, and take
out that `dump()`:

[[[ code('51d407c154') ]]]

Okay, status check! First, *everything* in Symfony is done by a service. Second,
bundles *give* us services. And third, we can control *how* those services are
instantiated via the different bundle configuration in `config/packages/`.

*Now*, let's go one important step further by creating our *own* service.

# Parameters

We know there's this *container* concept that holds all of our services... and we
can see the full list of services by running:

```terminal
php bin/console debug:container
```

## Listing Parameters

Well, it turns out that the container holds one *other* thing: grudges. Seriously,
don't expect to pull a prank on the service container and get away with it.

Ok, what it *really* holds, in addition to services, is *parameters*. These
are simple configuration values, and we can see them by running a similar command:

```terminal
php bin/console debug:container --parameters
```

These are basically variables that you can read and reference in your code. We
don't need to worry about most of these, actually. They're *set* by internal things
and *used* by internal things. But there *are* a few that start with `kernel`
that are pretty interesting, like `kernel.project_dir`, which points to the
*directory* of our project. Yep! If you ever need a way to refer to the directory
of your app, *this* parameter can help.

## Fetching Parameters from a Controller

So... how do we *use* these parameters? There are two ways. First, it's not super
common, but you *can* fetch a parameter in your controller. For example,
in `VinylController`, let's `dd($this->getParameter())` - which is a shortcut
method from `AbstractController` - and then `kernel.project_dir`. We even get some
nice auto-completion thanks to the Symfony PhpStorm plugin!

[[[ code('ef8f6d1274') ]]]

And when we try it... yep! There it is!

## Referencing Parameters with %parameter%

Now... delete that. This *works*, but most of the time, the way you'll use parameters
is by referencing them in your *configuration files*. And we've seen this before!
Open up `config/packages/twig.yaml`:

[[[ code('852f378720') ]]]

Remember that `default_path`? That's referencing the `kernel.project_dir` parameter. 
When you're in *any* of these `.yaml` configuration files and you want to reference 
a parameter, you can use this special syntax: `%`, the name of the parameter, 
then another `%`.

## Creating a new Parameter

Open up `cache.yaml`. We're setting `cache.adapter` to `filesystem` for *all*
environments. Then, we're overriding it to be the `array` adapter in the dev
environment only. Let's see if we can shorten this by creating a *new* parameter.

How *do* we create parameters? In any of these files, add a root key called
`parameters`. Below that, you can just... invent a name. I'll call it `cache_adapter`,
and set that to our value: `cache.adapter.filesystem`:

[[[ code('88f00b7bd8') ]]]

If you have a root `framework` key, Symfony will pass all of the config to
FrameworkBundle. The same is true with the `twig` key and TwigBundle.

But `parameters` is special: anything under this will create a *parameter*.

So yea... we now have a new `cache.adapter` parameter... that we're not actually
*using* yet. But we can already *see* it! Run:

```terminal
php bin/console debug:container --parameters
```

Near the top... there it is - `cache_adapter`! To use this, down here for `app`,
say `%cache_adapter%`:

[[[ code('9060cf5eae') ]]]

That's *it*. Quick note: You may have noticed that *sometimes* I use quotes in
YAML and *sometimes* I *don't*. Mostly, in YAML, you don't need to use quotes...
but you always *can*. And if you're ever not sure if they're needed or not, better
to be safe and *use* them.

Parameters *are* actually one example where quotes are *required*. If we didn't
surround this with quotes, it would look like a special YAML syntax and throw
an error.

Anyway, in the `dev` environment, instead of saying `framework`, `cache`, and `app`,
all we need to do is override that parameter. I'll say `parameters`, then
`cache_adapter`... and set it to `cache.adapter.array`:

[[[ code('e3fea12cdb') ]]]

To see if that's working, spin over here and run another helper command:

```terminal
php bin/console debug:config framework cache
```

Remember, `debug:config` will show you what your *current* configuration is under the
`framework` key, and then the `cache` sub-key. And you can see here that `app` is
set to `cache.adapter.array` - the resolved value for the parameter.

Let's check the value in the prod environment... just to make sure it's right there
too. When you run any `bin/console` command, that command will execute in the same
environment your app is running in. So when we ran `debug:config`, that ran in the
*dev* environment.

To run the command in the *prod* environment, we *could* go over here and change
`APP_ENV` to `prod` temporarily... but there's an *easier* way. You can *override*
the environment when running any command by adding a flag at the end. For example:

```terminal
php bin/console debug:config framework cache --env=prod
```

But before we try that, we always need to clear our cache first to see changes in
the `prod` environment. Do *that* by running:

```terminal
php bin/console cache:clear --env=prod
```

*Now* try:

```terminal
php bin/console debug:config framework cache --env=prod
```

And... beautiful! It shows `cache.adapter.filesystem`. So, the container *also* holds
parameters. This isn't a *super* important concept in Symfony, so, as long as you
understand how they work, you're good.

Ok, let's turn back to dependency injection. We know that we can autowire services
into the constructor of a service or into controller methods. But what if we need
to pass something that's *not* autowireable? Like, what if we wanted to pass one
of these *parameters* to a service? Let's find out how that works *next*.

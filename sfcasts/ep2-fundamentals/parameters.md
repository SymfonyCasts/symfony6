# Parameters

We know there's this *container* concept that holds all of our services, and we can see the full list of services by running:

```terminal
php bin/console debug:container
```

Well, it turns out that the container holds one *other* thing: *Parameters*. These are simple configuration values, and we can see them by running a similar command:

```terminal
php bin/console debug:container --parameters
```

These are almost like variables that you can read and reference in your code. We don't need to worry about most of these, actually. They're *set* by internal things and *used* by those internal things. But there *are* a few that start with `kernel` that are pretty interesting, like `kernel.projector_dir`, which points to the *directory* of our project. Yep! If you ever need a way to point to the directory of your project, *this* parameter can help.

So how do we *use* these parameters? There are two ways. First, it's not super common, but you can technically *fetch* a parameter in your controller. For example, in `VinylController.php`, let's `dd($this->getParameter())` which is a shortcut method from our abstract controller, and then `kernel.project_dir`. We even get some nice auto-completion thanks to the Symfony PhpStorm plugin. And when we try it... yep! There it is!

All right, let's delete that. This *works*, but ideally, the way you want to use parameters is by referencing them in your *configuration files*. And we've seen this before! Open up `/config/packages/twig.yaml`. Remember that `default_path`? That's referencing the `kernel.project_dir` parameter. When you're in *any* of these `.yaml` configuration files and you want to reference a parameter, you use this special syntax: A `%`, the name of the parameter, and another `%`.

Open up `cache.yaml`. We're setting the `cache.adapter` to the `filesystem` for *all* environments. Then, we're overriding it to be the `array.adapter` in the dev environment only. Here's the goal: We're going to create a *new* parameter, set it to the `cache.adapter`, and then use it to simplify our code in this file. The code in this file isn't very complex, but it's a good example of the power of parameters.

So how do we create a parameter? In any of these files, we need to add a root key called `parameters`. Below that, you can just invent a name. I'll call it `cache_adapter`, and set that to our value: `cache.adapter.filesystem`. If you have a root `framework` key, Symfony will pass all of this config to the FrameworkBundle. But if you have a root `parameters` key, that will create a *parameter*. Thanks to this, we have a new `cache.adapter` parameter. We're just not *using* it anywhere yet. But we can already *see* it if we re-run:

```terminal
php bin/console debug:container --parameters
```

Near the top... there it is - `cache_adapter`! Let's actually *use* this now. Down here, for `app`, I'm going to say `%cache_adapter%`. That's *it*.

Quick note: You'll notice that *sometimes* I'm using quotes and *sometimes* I'm *not*. For the most part in `.yaml`, you don't need to use quotes, but you always can. If you're ever not sure, *use* them. Parameters are actually one example where, if you don't surround it with quotes, it looks like a special syntax, and it will throw an error. That's why I'm using quotes here.

Anyway, in the dev environment, instead of saying `framework`, `cache`, and `app`, all we need to do is override that parameter. I'll say `parameters`, then `cache _adapter`... and I'll set this to `cache.adapter.array`. To see if that's working, spin over here and run another helper command:

```terminal
php bin/console debug:config framework cache
```

Remember, `debug:config` will show you what your current configuration is under the `framework` key, and then the `cache` sub-key. And you can see here, `app` is set to `cache.adapter.array` - the resolved value for the parameter.

Let's check the value in the prod environment just to make sure it's right there too. Whenever you run any `bin/console` command, that command is going to run in the same environment your app is running in. So when we ran `debug:config`, that's running in the *dev* environment. To run the command in the *prod* environment, we *could* go over here and change `APP_ENV` to `prod` temporarily, but there's an *easier* way. You can actually *override* the environment when you run any command by adding a flag at the end. If we want to run this in the `prod` environment, we can say:

```terminal
php bin/console debug:config framework cache --env=prod
```

But before we try that, we always need to clear our cache first to see changes in the prod environment. Do that by running:

```terminal
php bin/console cache:clear --env=prod
```

*Now* we can run:

```terminal
php bin/console debug:config framework cache --env=prod
```

Beautiful! It shows the `cache.adapter.filesystem`. Cool! The container *also* holds parameters. This isn't a *super* important concept in Symfony, so as long as you understand how they work, you're good.

So we know we can autowire services into our `__construct()` or controller methods, but what if we need to pass something that's *not* autowireable? What if we wanted to pass one of these *parameters* to our service? Let's find out how that works *next*.

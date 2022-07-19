# Environments

Our application is like a machine: it's a set of services and PHP classes that do
work... and ultimately render some pages. But we can make our machine work
*differently* by feeding it different *configuration*.

For example, in `SongController`, we're using the `$logger` service to log some
information:

[[[ code('ec32177521') ]]]

If we feed the logger some configuration that says "log everything",
it will log *everything*, including low level debug messages. But if we change the
config to say "only log errors", then this will *only* log errors. In other words,
the same machine can behave *differently* based on our configuration. And sometimes,
like with logging, we might need that configuration to be different while we're
developing locally versus on production.

To handle this, Symfony has an important concept called "environments". I don't mean
environments like local vs staging vs beta vs production. A Symfony environment
is a *set* of configuration.

For example, you can run your code in the `dev` environment with a set of
config that's designed for development. Or you can run your app in the `prod`
environment with a set of config that's optimized for production. Let me show you!

## The APP_ENV Variable

In the root of our project, we have a `.env` file:

[[[ code('40c34e875b') ]]]

We're going to talk more about this file later. But see this `APP_ENV=dev`? 
This tells Symfony that the current environment is `dev`, which is *perfect* 
for local development. When we deploy to production, we'll change this to `prod`. 
More on that in a few minutes.

But... what *difference* does that make? What happens in our app when we change this
from `dev` to `prod`? To answer, let me close some folders... and open
`public/index.php`:

[[[ code('7e152d62fa') ]]]

Remember: this is our front controller. It's the first file
that's executed on every request. We don't really care much about this file, but
its job is important: it boots up Symfony.

What's interesting is that it *reads* the `APP_ENV` value and passes it as the
first argument to this `Kernel` class. And... this `Kernel` class is actually *in*
our code! It lives at `src/Kernel.php`.

Cool. So what I want to know *now* is: What does the first argument to `Kernel`
control?

If we open the class we find... absolutely *nothing*. It's empty. That's
because the majority of the logic lives in this trait. Hold "cmd" or "control" and
click `MicroKernelTrait` to open that up.

## The config/packages/{ENV} Directory

The job of the `Kernel` is to load all of the services and routes in our app.
If you scroll down, it has a method called `configureContainer()`. Ooh! We know
what the container is now! And check out what it does! It takes this `$container`
object and imports `$configDir.'/{packages}/*.{php,yaml}'`. This line says:

> Yo container! I want to load all of the files from the `config/packages/` directory.

It loads all of those files, and then it passes the configuration from each to
whatever bundle is defined as the root key. But what's *really* interesting for
environments is this next line: `import`
`$configDir.'/{packages}/'.$this->environment.'/*.{php,yaml}'`. If you
dug a little, you'd learn that `$this->environment` is equal to the first argument
that's passed to `Kernel`!

In other words, in the `dev` environment, this will be `dev`. So, in addition
to the *main* config files, this will *also* load anything in the
`config/packages/dev/` directory. Yup, we can add *extra* config there
that *overrides* the main configuration in the `dev` environment. For example, we
could add logging config that tells the logger to log *everything*!

Below this, we also load a file called `services.yaml` and, if we have it,
`services_dev.yaml`. We'll talk more about `services.yaml` real soon.

## The when@{ENV} Config

So, if you want to add environment-specific configuration, you can put it in the
correct environment directory. But there's one *other* way. It's a pretty new feature
and we saw it at the bottom of `twig.yaml`. It's the `when@` syntax:

[[[ code('c2bc8660e5') ]]]

In Symfony, by default, there are *three* environments: `dev`, `prod`,
and then if you run automated tests, there's an environment called `test`. Inside
of `twig.yaml`, by saying, `when@test`, it means that this configuration will
only be loaded if the environment is equal to `test`.

The best example of this might be in `monolog.yaml`. `monolog` is the bundle
that controls the logger service. It *does* have some configuration that's used in
*all* environments. But, below this, it has `when@dev`. We won't talk too much
about the specific `monolog` configuration, but this controls how log messages are
handled. In the `dev` environment, this says that it should log *everything* and it
should log to a file, using this fancy `%kernel.logs_dir%` syntax that we'll learn
about soon.

Anyways, this points to a `var/logs/dev.log` file and the `level: debug` part means
that it will log *every* single message to `dev.log`... regardless of how important
or unimportant that message is.

Below this, for the `prod` environment, it's quite different. The most important
line is `action_level: error`. That says:

> Hi Ms Logger! This app probably logs a *ton* of messages, but I only want you
> to actually *save* messages that are an `error` importance level or higher.

That makes sense! In production, we don't want our log files filling up with tons
and tons of debug messages. With this, we only log *error* messages.

The big point is this: by using these tricks, we can configure our services
differently based on the environment.

## Environment-Specific Routing

And, we can even do the same thing with routes! Sometimes you have entire routes
that you only want to load in a certain environment. Back in `MicroKernelTrait`,
if you go down, there's a method called `configureRoutes()`. *This* is what's
responsible for loading *all* of our routes... and it's very similar to the other
code. It loads `$configDir.'/{routes}/*.{php,yaml}'` as well as this `dev`
environment directory, if you have one. We don't.

You can also use the `when@dev` trick. This file is responsible for registering
the routes used by the web debug toolbar. We *don't want* the web debug toolbar
in production... so these routes are *only* imported in the `dev` environment.

[[[ code('c32be9baf6') ]]]

Heck, certain *bundles* are only enabled in some environments! If you open
`config/bundles.php`, we have the name of the bundle... and then on the right,
the environments in which that bundle should be enabled. This `all` means *all*
environments.... and *most* are enabled in *all* environments.

The `WebProfilerBundle` however - the bundle that gives us the web debug toolbar
and profiler - is *only* loaded in the `dev` and `test` environments. Yup, the entire
bundle - and the services it provides - are *never* loaded in the `prod` environment.

So, now that we understand the basics of environments, let's see if we can switch our
application to the `prod` environment. And then, as a challenge, we'll configure our
cache service *differently* in `dev`. That's next.

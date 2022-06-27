# Environments

Our application is like a machine. It's a set of services and PHP classes that do a bunch of work, and ultimately render some pages. But we can make our machine work differently by feeding it different configuration. For example, in `SongController.php`, we're using the `$logger` service to log some information. But if we feed the logger some configuration that says "log everything", it will log *everything*, including low level debug messages. But if we change the config to say "only log errors", then this controller will *only* log errors. In other words, the same machine can behave *differently* based on our configuration. And sometimes, like with logging, we might need that configuration to be different while we're developing locally versus on production. To handle this, Symfony has an important concept called "environments". I don't mean different environments like local, staging, beta, or production. A Symfony environment is a *set* of configurations.

You can run your code in the dev environment with a set of configuration that's designed for development, or you can run your application in the prod environment with a set of configuration that's good for production. Let me show you!

In the root of your directory, you have a `.env` file. We're going to talk more about this file later, but see this `APP_ENV=dev` thing? This tells Symfony that the current environment is dev, which is perfect for local development. When we deploy to production, we'll change this to `prod`. We'll take a closer look at this in a few minutes.

So what difference does that make? What happens in our app when we change this from `dev` to `prod`? To answer that open, let me close some folders... and open `public/index.php`. Remember, this is our front controller. This is the first file that's executed on every request. And we don't really care much about the file itself, but its *job*. This function here basically boots up Symfony. What's interesting is that it actually reads the `APP_ENV` value and passes it to the first argument of this `Kernel` class. This `Kernel` class is actually *in* our code, at `src/Kernel.php`. So what I want to know is: What does the first argument to `Kernel` control?

If I open this up, I find... absolutely *nothing*. This is an empty class. That's because the majority of the logic is in this trait. Hold "cmd" or "control" and click `MicroKernelTrait` to open that up.

The job of the `Kernel` is to load all of the configuration and bundles in your application. If you scroll down here, it has a method called `configureContainer()`. Ooh! We know what the container is now, and check out what its job is! It takes this `$container` object and imports `$configDir.'/{packages}/*{php,yaml}'`. This line is saying:

`Hey container! I want you to go load all of the
files from the config/packages directory.`

So it loads all of those files, and then it passes the configuration from these to whatever bundle is defined as the root key. But what's *really* interesting for environments is this next line. It says `import($configDir).'/{packages}/'.$this->environment.'/*.{php,yaml}'`. If you trace to this class, you'll see that `$this->environment` is equal to the first argument passed to `Kernel`.

In other words, in the dev environment, this is going to be `dev`. In the dev environment, Symfony's also going to load anything in this `config/packages/dev/` directory. What this means is that we can add extra configuration inside of this file that *overrides* our normal configuration. So we can have a slightly different set of configuration in the dev environment. Down below here, it also loads a file called `services.yaml` and, if we have it, `services_dev.yaml`. We'll talk more about this `services.yaml` file a little bit later.

So, if you want to add environment-specific configuration, you can put it in an environment directory. But there's one *other* way to specify environment-specific configuration, and it's one of the newer features in Symfony. We actually saw it at the bottom of `twig yaml`. It's this special `when@` syntax.

In Symfony, by default, there are actually *three* environments. There's dev, prod, and then if you run tests, there's an environment called "test". Inside of `twig.yaml`, by saying, `when@test`, it means that this configuration here will only be loaded if the environment is equal to `test`. The best example of this might be in `monolog.yaml`. This `monolog` is the bundle that controls the logger service, and check it out! At the root, it *does* have some configuration that is used in all environments. But then, below this, it has `when@dev`. I won't talk too much about the `monolog` configuration, but this basically controls how the log messages are handled. In the dev environment, this says that it's going to log things to a file, and it uses this fancy syntax `%kernel.logs_dir%` to do that. We're going to talk about what these fancy `%` strings are in a few minutes, but what this points to is actually a `var/log/dev.log` file. And then the `level:debug` means that it's going to log every single log message to `dev.log`.

*That's* the configuration for the dev environment. Then, down here in the prod environment (I'll skip test), it's going to do something *very* different. Once again, I won't go into the specifics of what this configuration does. You can read more about it with the logger. But the important thing here, for example, is this `action_level: error`. It basically says:

`Hey logger! You're probably logging a *ton* of
messages in this application, but I only want you
to log messages that are an error level or higher.`

In production, we don't want our log files filling up with tons and tons of debug messages. With this, we only log *error* messages.

The big picture here is this: We're configuring our services based on the environment and then we're passing that environment-specific configuration to our bundles. You can do the same thing with routes! Sometimes you have entire routes that you only want to load in a certain environment. Back in `MicroKernelTrait.php`, if you go down, there's a method called `configureRoutes()`. This actually what's responsible for loading your routes, and it's very similar to the other configuration. It loads `$configDir.'/{routes}/*.{php,yaml}'`, as well as this dev environment directory. If you have one (We don't. It's something like `route/dev`), you can also use the `when@dev`. This file here is actually responsible for registering the routes used by the web debug toolbar. But we *don't want* the web debug toolbar in production. That would *not* be good. So these routes are *only* imported in the dev environment.

Heck, some bundles are only configured in a certain environment too. If you open `config/bundles.php`, you have the name of the bundle here, and then over here, you have the environments it should be enabled in. This `all` means *all* environments. Most of these are configured in all environments. The `WebProfilerBundle` however, is the bundle that gives us the web debug toolbar and profiler, so it's *only* loaded in the dev and test environment. The entire bundle and the services it provides are never loaded in the prod environment.

So, now that we understand the basics of environments, let's see if we can switch our application to the prod environment. And then, as a challenge, we'll configure our cache service *differently* in dev. That's next.

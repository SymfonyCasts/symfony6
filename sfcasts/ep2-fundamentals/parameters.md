We know that there is this container concept that holds all of the services and we
can see And we can see the full list of services by running `bin/console
debug:container`. Hope, it turns out that the container holds one other type of
thing, simple configuration values. These are called parameters and we can see them
by running that same 'debug:container' but with `--parameters`

Ohh These are almost like variables that you can read and reference in your code.
Now, most of these, we don't care about they're set by internal things and used by
those internal things. But there are a few that start with kernel that are
interesting, for example, `kernel.projector_dir`, which points to the directory of
our project. Yep. If you ever need a way to point to the directory of your project,
this parameter can help. So how do we use parameters? There are two ways. First It's
not super common, but you can technically fetch a parameter in your controller. For
example, an 'VinylController' that's DD and say `($this->getParameter)` which is a
shortcut method from our abstract controller and then `('kernel.project_dir')` even
get nice auto-completion thanks to the Symfony, uh, php storm plugin. And when I try
it, yep may, there is. All right. Let's delete that. The real main way that you use
parameters is by referencing them in configuration files. And we've seen this open up
config, packages, `Twig.yaml`. Remember that `default_path`. Well, that is
referencing the `kernel.project parameter`. If you are in any of these
yaml.configuration files, if you want to reference a parameter, you use this special
syntax '%the name of the parameter, and then another%'

Open up `cache.yaml` We're setting the cache.adapter to the filesystem for all
environments. Then we're overriding it to be the array.adapter in the dev
environment. Only here's the goal. We are going to create a new parameter, set it to
the cache adapter, and then use it to simplify our code in this file. Well, our code
in this file, isn't very complex, but it's a good example of the power of parameters.
How do we create a parameter In any of these files? Add a root key called
parameters:. Then below that you can just invent a name. How about cache_adapter? And
then I'll set that to our value `cache.adapter.filesystem`. So if you have a root
framework, key symfony will pass all of this config to the framework bundle. But if
you have a root parameters key, that will create a parameter. So thanks to this, that
we have a new cache adapter parameter. We're just not using it anywhere yet, But we
can already see it. If we re run `debug:container--parameters`, uh, near the top,
there it is. `Cache_adapter`. All right, now let's use this below.

So here for app:, I'm going to say '%cache_percent%'. That's it. Now quick note,
you'll notice that sometimes I'm using quotes and sometimes I'm not using quotes for
the most part in yaml. You don't need to use quotes, but you always can. So if you're
ever not sure surround the thing with quotes parameters are actually one example
where if you don't surround it with quotes, it looks like a special syntax. And it'll
throw an air, which is why I was using quotes here Anyways. Now in the dev
environment of, instead of saying `framework:`, `cache:`, `app:`, all we need to do
is override that parameter. So I'll say parameters And then `cache _adapter: Set to
cache.adapter.array`. Let's try it To see if that's working. Let's spin over here and
run another helper, command `bin/console debug:config framework cache`. Remember
debug:config will show you what your actual current configuration is under the
framework key, and then the cache sub key. So if we hit that, yes, you can see app:
is set to `cache.adapter.array`. The resolved value for the parameter. Let's check
the value in the prod environment just to make sure it's right there too.

Now, remember whenever you run any bin/console command that is running in whatever
environment your app is running in. So whenever you run debug:config, that's running
in the dev environment to run the command in the prod environment. We could go over
here and change app env to prod temporarily, but there's this easier way. Whenever
you run a command, whenever you're on any command, you can override the environment
with a `--env= prod` flag. Oh, but before we try that, we always need to clear our
cache first to see changes in the prod environment. So let's actually change this to
be a `cache:clear--env=prod`, and then `debug:config framework cahe --env=prod` and
beautiful. It shows the filesystem.adapter. Cool. So the container also holds
parameters. These aren't a hugely important concept in symfony, as long as you
understand how they work. You're good. Next we know we can autowire services into our
construct method or to our controller methods, but what if we need to pass something
that is not autowireable? Like what if we wanted to pass one of these parameters to
our service, let's help. Let's find out how that works next.

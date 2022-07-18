# Bundle Config (to Control Bundle Services)

We're now using the `HttpClientInterface` and `CacheInterface` services. Yay! But
*we* aren't actually responsible for *instantiating* these service objects.
Nope, they're created by something else (we'll talk about that in a few minutes),
and then just passed to us.

That's *great* because all of these services - the "tools" of our app - come ready
to use, out-of-the-box. But... if something *else* is responsible for instantiating
these service objects, how can we control them?

*Introducing*: bundle configuration!

## Bundle Configuration

Go check out the `config/packages/` directory. This has a number of different YAML
files, all of which are loaded *automatically* by Symfony when it first boots
up. These files all have exactly *one* purpose: to configure the *services* that
each bundle gives us.

Open up `twig.yaml`:

[[[ code('0100059e45') ]]]

For now, ignore this `when@test`: we're going to talk
about that in a few minutes. This file has a root key called `twig`. And so, the
entire purpose of this file is to control the services provide by the "Twig" bundle.
And, it's not the filename - `twig.yaml` - that's important. I could rename this to
`pineapple_pizza.yaml` and it would work *exactly* the same *and* be delicious.
I don't care what you think.

When Symfony loads this file, it sees this root key - `twig` - and says:

> Oh, okay. I'm going to pass whatever configuration is below to TwigBundle.

And remember! Bundles give us *services*. Thanks to this config, when TwigBundle is
preparing its services, Symfony passes it this configuration and TwigBundle
*uses* it to decide *how* its services should be instantiated... like what class
names to use for each service... or what first second or third constructor arguments
to pass.

For example, if we changed the `default_path` to something like
`%kernel.project_dir%/views`, the result is that the Twig service that renders
templates would now be pre-configured to look in that directory.

The point is: the config in these files give us the power to control the services
that each bundle provides.

Let's check out another one:  `framework.yaml`:

[[[ code('c5bd3da5ab') ]]]

Because the root key is `framework`, all of this config is passed to FrameworkBundle... 
which uses it to configure the services it provides.

And, as I mentioned, the filename doesn't matter... though the name *often* matches
the root key... just for sanity reasons: like `framework` and `framework.yaml`.
But that's not *always* the case. Open up `cache.yaml`:

[[[ code('ba42bd4e0b') ]]]

Woh! This is... just *more* config for FrameworkBundle! It lives in its own file... 
just because it's nice to have a separate file to control the cache.

## Debugging the Available Bundle Config

At this point, you might be asking yourself:

> Ok, cool... but what config keys are we *allowed* to put here? Where can I
> find which options are available?

Great question! Because... you can't just "invent" whatever keys you want: that would
throw an error. First, yes, you can, *of course*, read the documentation. But
there's *another* way: and it's one of my *favorite* things about Symfony's
config system.

If you want to know what configuration you can pass to "Twig" bundle, there are two
`bin/console` commands to help you. The first is:

```terminal
php bin/console debug:config twig
```

This will print out all of the *current* configuration under the `twig` key,
*including* any *default* values that the bundle is adding. You can see our
`default_path` set to the `templates/` directory, which comes from our config
file. This `%kernel.project_dir%` is just a fancy way to point to the root of our
project. More on that later.

Try this: change the value to `views`, re-run that command and... yup! We see
"views" in the output. Let me go ahead and change that back.

So `debug:config` shows us all of the *actual*, current config for a specific bundle,
like `twig`... which is especially handy since it also shows you *defaults* added
by the bundle. It's a great way to see *what* you can configure. For example,
apparently we can add a global variable to Twig via this `globals` key!

The second command is similar: Instead of `debug:config`, it's `config:dump`:

```terminal
php bin/console config:dump twig
```

`debug:config` shows you the *current* configuration... but `config:dump`
shows you a giant tree of *example* configuration, which includes *everything* that's
possible. Here you can see `globals` with some examples of how you could use that
key. This is a great way to see *every* potential option that you can pass to a
bundle... to help it configure its services.

Let's use this new knowledge to see if we can "teach" the cache service to store
its files somewhere else. That's next.

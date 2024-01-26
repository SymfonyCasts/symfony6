# Finding & Eliminating Deprecations

Symfony's deprecation system is a unicorn on the Internet: there's nothing
I know of that matches it. It's one of the things that makes Symfony so special
and a lot of work goes into!

## How Symfony Changes / Deprecates Features

Suppose we, in Symfony, want to change something: like the name
of a method. We can't just rename the method, because that would break your code.
Instead, we add the new method name, keep the old one, but add a little deprecation
code function *in* that old method. We release that on a minor version, like 6.3
or 6.4. Then, you upgrade to that version and, since your code is calling the old
method, it hits that deprecation, which triggers a deprecation warning. These
warnings are collected, and we can see them in various ways - like
on the Web Debug Toolbar.

Your job is to read these and update your code to call the *new* method name. Once
all the deprecations are gone, you can safely upgrade to Symfony 7.0. Because,
remember, the only difference between Symfony 6.4 and Symfony 7.0 is that the
deprecated code paths are gone. In our example, it means that the old method name
is finally removed in 7.0.

I *love* this process. It means that Symfony can change things and keep
modernizing, *and* we can update our apps in a safe and predictable way.
It's the best.

## Hunting Down Deprecations

So today, we're deprecation detectives: on a mission to hunt these down and
eliminate them. To get started, I'll manually clear my cache directory

```terminal
rm -rf var/cache/*
```

so that when we go over and refresh the homepage, this will *build* the cache. Some
deprecation warnings only happen while your cache is being built. Ok, it looks like
we have three warnings left after updating our recipes. Nice!

## Removing symfony/templating

Open those up. The first is related to some templating helper class being deprecated.
That's not something I remember using in my code. Look at the trace. Not
super helpful. Here's the helper class... called by a class loader.

This tells me that something is trying to use this class... and the class is
entirely deprecated. In fact, this entire `symfony/templating` component is
deprecated: it doesn't exist at all in Symfony 7.0! There's a pretty good chance
you never used it anyway... and I'm *not* using it in *my* app. So then, who is?

To find out, go to the command line and run:

```terminal
composer why symfony/templating
```

Ah! This is required by `knplabs/knp-time-bundle`. Click the link to jump
to our installed version: 1.20.0. But that's not the latest version: it now has
a version 2.2. We're way behind! The major version 2.0 modernizes the code... and
there's a good chance that included removing the templating dependency. In fact,
we see it down here.

This *is* a new major version of the package, so we *do* need to check the
changelog or release notes for any backwards compatibility breaks that might
affect us.

So the interesting thing about this first deprecation is that... it wasn't something
that *we* were calling directly. It's an indirect deprecation: caused by a library
we're using. And that's pretty common. To be ready for Symfony 7, we need to upgrade
this bundle.

In composer.json, search for "time"... then change this to the latest `^2.2`.
Spin over and run:

```terminal
composer up
```

It upgrades the package... and removes `symfony/templating`!

## DoctrineFixturesBundle False Deprecation

Ok, clear the cache again, close some tabs, and let's go to the "browse mixes"
page because that connects to the database. This time, we see two deprecations.
Open those up and dive in. The first has something to do with data fixtures.
If we look at the trace, it's not super obvious, but it's coming from
DoctrineFixturesBundle. This is a tricky one: I had to dive into the
DoctrineFixturesBundle GitHub repository to find a conversation. This time, the
deprecation is a false warning! The deprecation layer that was added to the bundle
wasn't done *quite* correctly. The maintainer confirms that it's fine... so when
we upgrade to Symfony 7, things won't break.

This is an odd situation, but it shows that hunting deprecations can be tricky!

## Deprecations from Doctrine

The final deprecation is longer and follows a different format. So far, each
message has included the package the deprecation lives in and the version
it was deprecated in. But we don't see that down here. And, at the end, it references
an issue from the `doctrine/orm` repository.

Ah! This deprecation isn't coming from Symfony: it's coming from
`doctrine/orm`! This tells us that we're going to need to make a change to our
code - at some point - before upgrading to the next major version of that package.
We're only focused on upgrading Symfony today, so this is *also* a deprecation we
can ignore.

So... yeah I think we're good. Our app is pretty small, but as I click around, the
only deprecations I see are those that we just looked at.

## Deprecation Log on Production

But... how can we be sure that there's not a page we forgot to check or a form submit
that triggers a deprecation? The answer: logging.

In `config/packages/monolog.yaml`, at the bottom, we have the production logging
config. The main handler is the `nested` handler: this logs errors on production.
This logs them to stderr, or you could change that to a file.

The point is: you're hopefully collecting your production errors somewhere. At the
bottom, there's another handler called `deprecation`. This logs all deprecation
notices to the *same* place. So in your production error logs, you should *also*
see deprecations warnings.

So: fix all the deprecations you can find, deploy to production, wait a day or two,
then check your logs to see if there are any deprecations. Once there
aren't, you're safe to move to Symfony 7.0. Let's do that upgrade next!

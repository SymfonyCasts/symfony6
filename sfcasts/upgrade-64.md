# Upgrading to Symfony 6.4

Hey everyone! Symfony 7 is out! Woo! Well, of course *I'm* excited - I love all things
Symfony, Twig, related. But what does it really *mean* that Symfony 7 is out?

## Symfony's Delightfully Predictable Release Schedule

Honestly... not much! Thanks to Symfony's release schedule, a new major version
isn't much of a big deal... though we try to pretend it is for marketing.

Every 6 months - in May & November - a new minor version
is released, like 6.1 or 6.2. *Those* are the versions that contain new features.
So it *totally* makes sense to get excited about Symfony 6.3 or fantastically amazing
new features in Symfony 6.4. Then, each ".4" version,
like 6.4, is released on the *same day* as the .0 version of the next major: 7.0.
Yea, 6.4 and 7.0 were released on the *exact* same day and are, effectively, identical!
They're twins!

The only difference is that, in 7.0, all the deprecated
code paths are removed. And this is the core of what makes Symfony special. The
release schedule and the deprecation policy mean that as users, we can
upgrade our apps forever across major versions... without it
being a big deal or breaking our apps. And that's exactly what we're going
to do in this tutorial... followed by a tour of some of my favorite new features.

## Project Set Up

As always, to get the most out of this tutorial, code along with me by downloading
the course code from this page. After you unzip the file, you'll find a `start/`
directory with the same code that you see here. The `README.md` file tells an
inspiring tale of how to get the application up and running. I've already done
most of the steps, including running `yarn install` and `yarn watch` in this tab.

The final step is to use the `symfony` binary to run:

```terminal
symfony serve -d
```

to start a development web server. I'll click the link. Say hello to Mixed Vinyl:
The app from several of our Symfony 6 tutorials, which is currently on 6.1.2.

## Using a Newer PHP Version

Open up `composer.json`. Near the top, our app requires `php` 8.1 or greater.
In my apps, down under `config.platform.php`, I also like to set the *specific*
PHP version that we're using on production. This guarantees that Composer
only gives me dependencies compatible with that version.

Locally, if I run `php -v`, I already have PHP 8.3 installed. I *also* have
a second `php` binary installed for version 8.1. And thanks to the `8.1` in `composer.json`,
when I started the `symfony` web server, it used *that* older version.

Change this to, just PHP `8.3`. Then run:

```terminal
composer up
```

In `composer.json`, all my dependencies - whether they're Symfony or something else -
are written in a way that only allows the last number or the *second* to last number
to change. Assuming the package maintainers are doing their job, those updates *won't*
contain backwards-compatibility breaks. We should be able to upgrade from 6.1 to
6.4... or 2.0 to 2.4 and our app *should* keep rocking like normal!

So running `composer up` to get these updates, in theory, is totally safe.

## Encore & Minor Changes

Over in my `yarn` tab, the update triggered an error: something about a controller
does not exist. This is special to Symfony UX & Encore. When you update your
PHP dependencies, you may need to reinstall your `node` dependencies. Hit
Ctrl+C, then run:

```terminal
yarn install --force
```

Or `npm install --force` if you're using `npm`. Then

```terminal
yarn watch
```

again. It's happy! In the main tab, run:

```terminal
git status
```

Alongside the usual suspects, there's a new controller in `controllers.json`...
which came from an update to `ux-turbo`. We won't use it, but it's fine there.
In `package.json`, it added a new entry for stimulus bundle. This is a relatively
new bundle that got installed during the upgrade, and we'll talk more about it soon.

## Upgrading to 6.4

So we are *now* using PHP 8.3 and we've upgraded our dependencies a bit. But we're
still using Symfony 6.1. To upgrade to 7, we first need to upgrade to 6.4.
That'll give us a chance to prep for 7.0 by finding - and fixing - all the deprecations.

And... upgrading is easy! Find `6.1.*`, replace with `6.4.*` and replace all.

Though, be careful. Most of the time, the Symfony version constraints look like this.
However, they *could* look like `^6.1`. So don't miss those: the goal
is to upgrade every `symfony` package that comes from the *main* repository.
That... can be confusing because, mixed in with the packages that we *do* want
to upgrade are other packages that live under `symfony`, but are independent
and follow their own release timeline & versioning. Ignore those for now - but
we *will* make sure *every* package is upgraded by the end.

Also, near the bottom, under `extra.symfony.require`, make sure this is also updated
to `6.4.*`. That's a composer optimization that tells it to only worry about 6.4
Symfony versions.

Back over at the terminal, let's do this!

```terminal
composer up
```

Look at those beautiful upgrades from 6.1 to 6.4! *And*... when we try the site,
the stinkin' thing still works! 

Oh, but check out the PHP version: 8.1.27. When we started the `symfony` web server,
it read the PHP 8.1 version from `composer.json`, found that version installed on
my machine and used it. We changed this to 8.3, but we need to restart the server
to use it. Run:

```terminal
symfony server:stop
```

Then:

```terminal
symfony serve -d
```

Yup: it found the PHP 8.3.1 version on my system. And on the site... got it!

Ok, this is working on Symfony 6.4. Our job *now* is to find every deprecation and
fix them. On the web debug toolbar, we apparently hit 22 deprecated code
paths on this page! To start fixing these, we'll... cheat... take a shortcut, by
upgrading our Flex recipes.

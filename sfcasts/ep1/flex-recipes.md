# Flex Recipes

We just installed a new package by running `composer require templates`. *Normally*
when you do that, Composer will update the `composer.json` and `composer.lock` files,
but nothing else.

But when I run:

```terminal
git status
```

There are *other* changes. This is thanks to Flex's recipe system. Each time we
install a new package, Flex checks a central repository to see if that package has
a recipe. And if it does, it installs it,

## Where do Recipes Live?

Where do these recipes live? In the cloud... or more specifically GitHub. Check
it out. Run:

```terminal
composer recipes
```

This is a command *added* to composer by Flex. It lists all of the recipes that
have been installed. And if you more information about one, just run:

```terminal
composer recipes symfony/twig-bundle
```

This is one of the recipes that was just executed. And... cool! This shows us
a couple of nice things! The first is a tree that shows you which files it *added*
to our project. And second, it shows the URL to the recipe that was installed.
I'll click to open that.

Yep! Symfony recipes live in a special repository called `symfony/recipes`. This
repository is a big directory organized by package name. There's a `symfony`
directory that holds recipes for all packages starting with `symfony/`. The one
we were just looking at is way down here: `twig-bundle`. And then there are different
versions of the recipe based on your version of the package. We're using the latest
5.4 version.

Every recipe has a `manifest.json` file, which controls what it does. The recipe
system can only do a specific set of operations, including adding new files
to your project and modify a few *specific* files. For example, this `bundles` section
tells flex to add this line to our `config/bundles.php` file.

If we run `git status` again... yup! That file *was* modified. If we diff it:

```terminal-silent
git diff config/bundles.php
```

It added *two* lines, probably one for each of the *two* recipes.

## Symfony Bundles?

By the way, this `config/bundles.php` file is not one that you need to think about
much. A bundle, in Symfony land, is basically a plugin. So if you install a new bundle
into your app, that gives you some new Symfony features. In order to *activate* that
bundle, it needs to live in this file.

So the first thing that the recipe did for twig-bundle, thanks to this line up here,
was to activate itself inside `bundles.php`... so that we don't have to do it manually.
Recipes are like automated installation.

The *second* section in the manifest is called `copy-from-recipe`. This is simple:
it says to copy the `config/` and `templates/` directories from the recipe into the
project. If we look... the recipe contains a `config/packages/twig.yaml` file...
and also a `templates/base.html.twig` file.

Back at the terminal, run `git status` again. We see these two files at the bottom:
`config/packages/twig.yaml`... and inside of `templates/`, `base.html.twig`.

I *love* this. Think about it: if you install a templating tool into your app,
we're going to need some configuration *somewhere* that tells that templating tool
what directory to look inside of to find your templates. Whelp, go check out that
`config/packages/twig.yaml` file. We're going talk about all of these Yaml files
in the next tutorial. But on a high level, this file controls how Twig - the name
of the templating engine - behaves. And check out the key: `default_path` set
to `%kernel.project_dir%/templates`. Don't worry about this fancy percent syntax:
that's a fancy way to point to the root of our project.

The point is, this config says:

> Hey Twig! When you look for templates, you should look in the `templates/` directory.

And then the recipe even *created* that directory with a layout file inside. We'll
use this in a few minutes.

## symfony.lock & Committing Files

The *last* unexplained file that was modified is `symfony.lock`. This is *not*
important: it just keeps track of which recipes have been installed... and you
*should* commit it.

And you should just commit it. In fact, we should commit *all* of this stuff. The
recipe might give us files, but they are *our's* to modify. Run:

```terminal
git add .
```

Then:

```terminal
git status
```

Let's commit!

```terminal
git commit -m "Adding Twig and its beautiful recipe"
```

## Updating Recipes

Done! By the way, a few months down the road, there might be changes to some
recipes that you've installed. And if there *are*, when you run

```terminal
composer recipes
```

You'll see a little "update available" next to them. Run `composer recipes:update`
to upgrade to the latest version. Oh, and by the way, in addition to `symfony/recipes`,
there is also a `symfony/recipes-contrib` repository. So recipes can live in
*either* of these two places. The recipes in `symfony/recipes` are approved by
Symfony's core team, so they're a bit more vetted for quality. Other than that,
there's no difference.

## Our Project Started as One File

Now, the recipe system is *so* powerful that *every* single file in our project was
added via a recipe! I can prove it. Go to https://github.com/symfony/skeleton.

When we originally ran that `symfony new` command to start our project, what that
*really* did was *clone* this repository... and then ran `composer install` inside
of it, which downloads everything into the `vendor/` directory.

Yup! Our project - the one that you see right here - was originally *just* a
`composer.json` file. But then, when the packages were installed, the *recipes*
for those packages added *everything* else that you see. Run:

```terminal
composer recipes
```

again. One recipe is for something called `symfony/console`. Check out *its*
recipe's details:

```terminal
composer recipes symfony/console
```

And... es! The recipe for `symfony/console` added the `bin/console`. The recipe
for `symfony/framework-bundle` - one of the other packages that was originally
installed - added almost everything else we see, including the `public/index.php`
file. How cool is that?

Okay, next we installed twig. Let's get back to work and use it to render some
templates!

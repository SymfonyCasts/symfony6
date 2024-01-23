# Encore, StimulusBundle & their Recipe Changes

Let's keep upgrading recipes.

## symfony/twig-bundle Recipe Update

Next up is TwigBundle. This has a conflict in the one
file it updated: `templates/base.html.twig`.

And... it's odd. You can see our custom content here.... then the default
title with the default favicon down below. Keep our custom stuff, and delete this
comment. We don't need that.

Run:

```terminal
git add templates
```

Then:

```terminal
git diff --cached
```

This shows `symfony.lock` of course, but there *was* a change to `base.html.twig`:
it removed `encore_entry_link_tags()` and `encore_entry_script_tags()`. Why?

## The Rearranging of Recipes

One big recent addition to the Symfony frontend world was StimulusBundle. On
its own, that's no big deal. *But*, when it was introduced, various recipes
were rearranged. A few changes that used to live in the recipe for one package
packed up and moved to another.

For example, these lines used to be part of TwigBundle's recipe, but they
moved to the recipe for WebpackEncoreBundle. So when we update the TwigBundle
recipe, it *looks* like these lines should be *removed*.

Of course, we *do* still need these, but accept this change temporarily.
We'll see these get added back later when we upgrade the WebpackEncoreBundle recipe.

## symfony/webpack-encore-bundle Recipe Update

Ok, commit this and... let's do our last recipe update: WebpackEncoreBundle!

And... more conflicts. We can't catch a break. Run:

```terminal
git status
```

Ok, in `package.json`, we have a number of changes. The recipe is trying to upgrade
us from Encore version 3 to 4. The biggest difference between 3 and 4 is that it's
now your responsibility to have a few packages in *your* `package.json`, like
`webpack` itself... or the babel packages.

Let's keep version 4... and keep everything else. This is a mixture of custom
packages that we've added and the new ones needed for Encore 4.

Run:

```terminal
git add package.json
```

Then check out what else changed with `git diff`. Some meaningless config,
`package.json` and `symfony.lock`. `webpack.config.js` holds some low-level changes:
using a newer version of core.js and the `plugin-proposal-class-properties` isn't
needed anymore.

So, boring, but all good stuff! Commit that recipe. And because we just updated
`package.json`, in the other tab, hit Control+C to stop `yarn`. Then run

```terminal
yarn install
```

to get the latest node dependencies... and

```terminal
yarn watch
```

to restart the process. Hey, we're now building with Encore 4! Go team!

## Upgrading WebpackEncoreBundle to v2

The biggest change in the Encore world was really the introduction of StimulusBundle.
Related to this, in `composer.json`, `symfony/webpack-encore-bundle` has a new
major version. Change this to `^2.0`.

Then spin over and, on your main terminal tab, run:

```terminal
composer up
```

By the way, this will fail at the bottom with something related to
SensioFrameworkExtraBundle. We... kinda broke our app in the previous chapter while
upgrading the framework bundle recipe. We'll fix this in the next chapter, but
it's not hurting anything right now.

So what changed between version 1 and 2 of WebpackEncoreBundle? Just one thing:
the Twig `stimulus_` helper functions - like `stimulus_controller()` - were removed
and moved into the new StimulusBundle. No big deal.

The *real* tricky part is what I mentioned earlier: as a result of the new bundle,
a bunch of recipe parts were rearranged between packages. In
addition to the `encore_entry()` Twig functions moving to WebpackEncoreBundle's
recipe, certain files - like `assets/controllers.json` - were moved from
WebpackEncoreBundle's recipe to StimulusBundle's recipe.

This is *all* good: the new situation is cleaner with Stimulus-related files
living in *that* bundle's recipe. But... it makes for a bit of a mess when upgrading
the recipes.

So let's walk through that. Run:

```terminal
git status
```

Commit these changes... then run

```terminal
composer recipes
```

again. Surprise! There are two new updates! Where did those come from? Well, we
just upgraded StimulusBundle and WebpackEncoreBundle and *those* new versions have
new *recipe* versions.

## symfony/stimulus-bundle Recipe Update

Update `symfony/stimulus-bundle`. This... is where the weird starts. Run:

```terminal
git status
```

We have a conflict in `assets/controllers.json`. This file already existed and
the recipe tried to add it. That's because StimulusBundle is now responsible for
adding this file... and it's confused because it's already here. Fix this by
keeping our `controllers.json` file exactly how it was.

Add that, then `git diff` to see the other changes. Ok, it added an import line
to `app.js`. That's also not something we want because... we already have it down
here! It's another example of the recipe doing something that's already done.
Remove that from the top... then `git add` that file.

And... everything else is fine. It gave us a new `hello_controller.js`,
which you can keep or remove, and `symfony.lock`. All good.

## symfony/webpack-encore-bundle V2 Recipe Update

Commit that... then onto our final update for WebpackEncoreBundle. This one is
particularly strange. Run:

```terminal
git status
```

Two conflicts. Many of the files here *used* to live in WebpackEncoreBundle's recipe,
but were moved out of it. So when we upgrade the recipe, it *looks* like a
bunch of stuff should be *deleted*. In `assets/app.js`, this file wasn't deleted,
but it's trying to remove its guts. Keep it how it was before. Then add it to `git`.

Next up is `package.json`. It's... kind of the same thing: it's trying to delete
stuff. Don't let it get away with that! Keep our code... then add this file to
`git` too.

Ok, let's see how things look. It wants to delete `assets/bootstrap.js` - we don't
want that - and it also wants to delete `controllers.json`. We *also* don't want
that. We don't want any of these changes... especially not the letter "G" that I
apparently just typed into `package.json`! There's really only one change we
care about: in `base.html.twig`. Tada! It's adding back
`encore_entry_link_tags()` and `encore_entry_script_tags()`.

That *is* a good change. For the final file - `webpack.config.js` - it wants to
remove `enableStimulusBridge()`. Because we *are* using Stimulus, we *do* still
want that. Run

```terminal
git reset HEAD
```

to move everything out of the staging area of git, then

```terminal
git checkout assets webpack.config.js
```

to undo those changes. Perfect. We're left with `symfony.lock` and `base.html.twig`.
Commit those. 

And we are good! We're rocking the latest version of WebpackEncoreBundle with the
latest version of WebpackEncore *and* we've gone through that weird, one-time recipe
update.

Unfortunately, earlier, we busted our app. So next up: remove SensioFrameworkExtraBundle.

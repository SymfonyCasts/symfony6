# Upgrading to Symfony 7

All the relevant deprecations are gone: we are ready for Symfony 7.0.

## Updating composer.json

Doing the actual upgrade is... almost disappointingly easy in `composer.json`
replace `6.4.*` with `7.0.*`.

That's it. Spin over and run:

```terminal
composer up
```

## Finding Blocking Packages

There's a good chance this won't work. Yup! Something is blocking the update!
The first thing I see is `babdev/pagerfanta-bundle`. Apparently, it works with
Symfony 4, 5 and 6, but not 7.

There's a good chance that I probably need to upgrade this to a new version that
*does* support Symfony 7. Run:

```terminal
composer outdated
```

Sure enough: there are three pagerfanta packages that all have a new major version.
In `composer.json`, search for pagerfanta. Change all of these to `^4.0` to get
that new major version.

And because this *is* a major version upgrade, I won't do it, but you should check
the repository for each package and find the changelog or release notes that talk
about any backwards compatibility breaks when going from version 3 to 4.

Ok, try the update again:

```terminal
composer up
```

And... *still* no dice! Hmm: it says that the *root* `composer.json` - meaning *our*
`composer.json` - requires `symfony/proxy-manager-bridge` `7.0.*` but it didn't find
a version 7.

Sure enough, this package lives directly in *our* `composer.json` file. Proxies are
something that Doctrine uses behind the scenes to load lazy relationships. Recently,
Symfony added its *own* version of proxies called "ghost objects". They're spooky
cool. Anyway, this proxy package isn't needed anymore. This was originally added
to our app *way* back when we installed Doctrine: it *used* to be part of the
`orm-package`.

It's no longer something we need, so remove it.

Ok, try composer up again:

```terminal-silent
composer up
```


And... it works! Look at all those beautiful Symfony 7 updates! And best of all,
when we go to the site, it works too! And of course it does! We handled the
deprecations correctly, so there are no surprises when we finally get to 7.0.

## Any other Packages to Update

At this point, I like to check to see what *other*, non-Symfony packages I might
have that are outdated. Run `composer outdated` again:

```terminal-silent
composer outdated
```

Woh! Just two! `doctrine/lexer` and a `php-parser`. To find out why this didn't go
to version 3, copy that package name, and run

```terminal
composer why-not doctrine/lexer 3.0
```

Hmm: our version of `doctrine/orm` requires `doctrine/lexer` version 2. And since
we didn't see `doctrine/orm` as an outdated package, it means that there simply isn't
a version of `doctrine/orm` yet that works with `doctrine/lexer` 3. And that's fine
That's a low-level package and we're in no rush.

The other package - the `php-parser` - I can tell you, without even looking, that
this is required by `symfony/maker-bundle`, and in its next release, version 5
will be allowed. But it's also not something that we really care about.

## New Version Recipes

Because we just updated some packages, if we run `composer recipes`... hey! There
are two new recipe updates available!

```terminal-silent
composer recipes
```

We can't upgrade these until we have everything committed... so do that. I'll
include an emoji for flare. Then run:

```terminal
composer recipes:update
```

And `git diff --cached` to see the changes. This is *cool*: a bunch of lines removed.
These are being removed because these are now the *default* values. The `session`
key no longer needs this stuff - they're are all the default values... and same
`php_errors` and `handle_all_throwables`. It's cleaning up our config, which
is nice!

Commit that, then run `recipes:update` one more time:

```terminal-silent
composer recipes:update
```

This is the same thing: it removes a config option that is now the default.
Commit that. Our project is now jsut a *little* bit cleaner.

*So* we're on Symfony 7, our app is working, our recipes are updated!

## Changing the Namespace for #[Route]!

While we're here, inside a controller, notice it highlights the `Route` attribute:

> Symfony Annotation namespace will be deprecated in Symfony 6.4 /7.0.

Look at the `use` statement: it has `Annotation` in the namespace! This class
isn't *yet* deprecated, but it will be soon. And fixing it is simple. Delete
the `use` statement, go down here, click on the class, hit Alt+Enter, Import Class,
then get the one from the `Attribute` namespace.

Copy that... then repeat in the other two controller files. This will save us
a deprecation in the future.

Now that we're on Symfony 7, I want to do something *optional*, but really cool:
I want to remove Webpack Encore and replace it with AssetMapper.

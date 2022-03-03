# Symfony Flex: Aliases, Packs & Recipes

Symfony is a set of libraries that give you tons of tools: tools for logging, making
database queries, sending emails, rendering templates, making API calls, just to
name a few. If you counted them, Symfony consists of about 100 separate libraries.
Wow!

Right now I want to start turning our pages into true HTML pages... instead of just
returning text. But we are *not* going to jam a bunch of HTML into our PHP classes.
Yuck. Instead, we're going to render a template.

## Symfony's Start Small & Install Features Philosophy

But guess what? There is no templating library in our project right now. What? But
I thought you just said that Symfony has a tool for rendering templates!? Lies!

Let me explain. Symfony *does* have a ton of tools. But our app currently uses very
*few* of the Symfony libraries. The tools we have so far don't amount to much more
than a route-controller-response system. If you need to render a template or make
a database query, we do *not* have tools installed in our app to do that... yet.

I actually *love* this about Symfony. Instead of starting us with a gigantic project,
with everything we need, plus tons of stuff that we *don't* need, Symfony starts tiny.
*Then*, if you need something, you install it!

But before we install a templating library, at your terminal, run:

```terminal
git status
```

Let's commit everything:

```terminal
git add .
```

I can safely do `git add .` - which adds *everything* in my directory to git - because
one of the files that our project *originally* came with was a `.gitignore` file,
which already ignores stuff like the `vendor/` directory, `var/` directory, and
several other paths. If you're wondering what these weird marker things are, that's
related to the recipe system, which we're about to talk about.

Anyways, run `git commit` and add a message:

```terminal-silent
git commit -m "route -> controller -> response -> profit"
```

Perfect! And now we are clean.

## Installing a Templating Library (Twig)

Okay. So how can we install a templating? And what templating libraries are even
*available* for Symfony? And which is recommended? Well, of course, one great way
to answer this would be check the Symfony documentation.

But we can also just... guess! In *any* PHP project, you can add new third-party
libraries to your app by saying "composer require" and then the package name.
We don't *know* the package name we need yet, so I'll just guess:

```terminal
composer require templates
```

Now, if you've used Composer before, you might be screaming at your screen right
about now! Why? Because in Composer, package names are *always* `something/something`.
It is literally *not* possible to have a package just named `templates`.

But watch: when we run this, it works! And up on the top, it says using version 1
for `symfony/twig-pack`.

## Flex Aliases

Ok, we need to take a tiny step backwards. Our project started with a `composer.json`
file containing  several Symfony libraries. One of these is called `symfony/flex`.
Flex is a composer *plugin*. So it *adds* more features to composer. Actually, it
adds *three* superpowers to composer.

The first, which we just saw, is called Flex aliases. Head to https://flex.symfony.com
to see a *giant* page full of packages. Search for "templates". Here it is.
Under `symfony/twig-pack`, it says Aliases: template, templates, twig, and twig-pack.

The idea between behind Flex aliases is dead simple. We type
`composer require templates`. And then, internally, Flex *changes* that to
`symfony/twig-pack`. And ultimately, that's what Composer installs.

This means that, most of the time, you can just "composer require" whatever you want,
like `composer require logger`, `composer require orm`, `composer require icecream`,
whatever. It's just a shortcut system. The important point is that, what *really*
got installed was `symfony/twig-pack`.

## Flex Packs

And *that* means that, in our `composer.json` file, we *should* now see
`symfony/twig-pack` under the `require` key. But if you spin over, it's not there!
Instead, it added `symfony/twig-bundle`, `twig/extra-bundle`, and `twig/twig`.

We're witnessing the *second* superpower of Symfony Flex: unpacking packs.
Copy the original package name and... we can actually find that repository on GitHub
by going to https://github.com/symfony/twig-pack.

And... it contains just *one* file: `composer.json`. And *this* requires three
*other* packages: the three we just saw added ti *our* project.

This is called a Symfony pack. It's... really just a fake package that helps us
install *other* packages. It turns out, if you want a rich template engine to be
added to your app, we recommend installing these *three* packages. But instead of
making you install add of of these manually, you can require the `symfony/twig-pack`
and get them automatically. When you install a "pack", like this, Flex automatically
"unpacks" it: it finds the three packages that the pack depends on and adds *those*
into your `composer.json` file. Packs are a shortcut so that you can run one
`composer require` command and get *multiple* libraries added to your project.

Ok, so what is the third and final superpower of Flex? To find out, at your terminal,
run:

```terminal
git status
```

## Flex Recipes

Whoa. Normally when you run `composer require`, the only files it should modify -
other than downloading the files into `vendor/` - are `composer.json` and
`composer.lock`. Flex's third superpower is its recipes system.

So: whenever you install a package, that package *may* have a *recipe*. If it does,
in addition to downloading that package into the `vendor/` directory, Flex will
*execute* that recipe. Recipes can do things like add new files or even modify a
few existing files.

Watch: if we scroll up a little, ah yes: it says "configuring two recipe". So
apparently there was a recipe for a `symfony/twig-bundle` and also a recipe for
`twig/extra-bundle`. And these recipes apparently updated the `config/bundles.php`
file, added a new directory and file.

The recipe system is *sweet*. All *we* need to do is composer require a new library
and its recipe will then add all the configuration files or other setup needed
so that we can start *using* that library immediately. No more following 5 manual
"installation" steps on a README. When you add a new library, it works out-of-the-box.

Next: I want to dive a bit deeper into the recipes. Like, where do they live? And
what did this recipe added specifically to our app and why? I'm also going to let
you in on a little secret: *every* single file on our project - all the files in
`config/`, the `public/` directory... *all* of this stuff - was added via a recipe.
And I'll prove it

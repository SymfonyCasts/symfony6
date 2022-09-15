# Installing Doctrine

Welcome back team to episode *three* of our Symfony 6 series! The first two courses
were *super* important: taking us from the basics up through the *core* of how
*everything* works in Symfony: all that good "services" & configuration stuff. You
are now ready to use *any* other part of Symfony and *really* start building out
a site.

And... what better way to do that than to add a database? Because... so far,
for all the cool things we've done, the site we've been building is 100% static.
Boring! Time to change that.

## Hello Doctrine

So we know that Symfony is a collection of a *lot* of libraries for solving a *ton*
of different problems. So... does Symfony have some tools to help us talk to the
database? The answer is... no! Because... it doesn't have to!

Why? Enter Doctrine: *the* most powerful library in the PHP world for working
with databases. And Symfony and Doctrine work *great* together: they're the Frodo
and Sam Gamgee of PHP middle earth: the Han Solo and Chewbacca of the PHP Rebel
Alliance. Symfony & Doctrine are like two Disney characters that finish each other's
sandwiches!

## Project Setup

To see this dynamic duo in action, let's get our project set up. Playing with
databases is fun, so code along with me! Do that by downloading the course code
from this page. After unzipping it, you'll find a `start/` directory with the same
code that you see here. Pop open this `README.MD` file for all the setup instructions.

The last step will be to open a terminal, move into your project and run:

```terminal
symfony serve -d
```

This uses the Symfony binary to start a local web server which lives at
https://127.0.0.1:8000. I'll take the lazy way out and click that to see...
Mixed Vinyl! Our latest startup idea - and I swear, this one is going to be *huge* -
combines the nostalgia for the "mix tapes" of the 80's and 90's with the audio
experience of vinyl records. You craft your sweet mix tapes, then we press them
onto a vinyl record for a *full* hipster audio experience.

So far, our site has a homepage *and* a page to browse mixes that *other* people
created. Though, that page isn't *really* dynamic: it pulls from a GitHub repository...
and unless you've configured an API key like we did in the last episode, this page
is broken! That's the *first* thing we'll fix: by querying a databasse for the mixes.

## Installing Doctrine

So let's get Doctrine installed! Find your terminal and run:

```terminal
composer require "doctrine:^2.2"
```

This is, of course, a Flex alias for a library called `symfony/orm-pack`. And
remember: a "pack" is a, sort of, "fake library" that serves as a shortcut to
install *several* packages at once. In this case, we're installing Doctrine itself,
but also a few other relataed libraries, like the excellent Doctrine Migrations
system.

## Docker Configuration

Oh, and check this out! The command is asking:

> Do you want to include Docker configuration from recipes?

So, occasionally when you install a package, that package's recipe will contain
Docker configuration that can, for example, start a database container. This is
totally optional, but I'm going to say `p` for yes permanently. We'll talk more about
the Docker configuration in a few minutes.

## The Doctrine Recipes

But right now, let's check out what the recipe did. Run:

```terminal
git status
```

Okay cool: this modified the normal files like `composer.json`, `composer.lock` and
`symfony.lock`... and it *also* modified `config/bundles.php`. If you check that
out... no surprise: our app now has *two* new bundles: DoctrineBundle and
DoctrineMigrationsBundle.

[[[ code('0dca528440') ]]]

But probably the most important part of the recipe is the change it made to our
`.env` file. Remember: this is where we can configure environment variables... and
the recipe gave us a *new* one called `DATABASE_URL`. This, as you can see, holds
all the connection details, like the username and password.

[[[ code('c05f3c21ff') ]]]

What *uses* this environment variable? Excellent question! Check out a new file
the recipe gave us: `config/packages/doctrine.yaml`. Most of this config you
won't need to think about or change. But notice this `url` key: it reads
that `DATABASE_URL` environment variable!

[[[ code('e64af17f6d') ]]]

The point is: the `DATABASE_URL` env var is the *key* to setting up your app to
talk to a database... and we'll play with it in a few minutes.

The recipe also added a few new directories: `migrations/` `src/Entity/` and
`src/Repository/`. Right now, other than a meaningless `.gitignore` file, these
are all empty. We'll start filling them up real soon.

Ok: Doctrine *is* now installed. But to talk to a database... we need to make
sure we have a database running *and* that the `DATABASE_URL` environment variable
is pointing to it. Let's do that next, but with an optional & delightful twist:
we're going to use Docker to start the database.

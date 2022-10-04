# Doctrine Extensions: Timestampable

I *really* like adding timestampable behavior to my entities. That's where you have
`$createdAt` and `$updatedAt` properties that are set automatically. It just... helps
keep track of when things happened. We added `$createdAt` and cleverly set it by
hand in the constructor. But what about `$updatedAt`? Doctrine *does* have an awesome
event system, and we *could* hook into that to run code on "update" that sets that
property. *But* there's a library that *already* does that. So let's get it installed.

## Installing stof/doctrine-extensions-bundle

At your terminal, run:

```terminal
composer require stof/doctrine-extensions-bundle
```

This installs a small bundle, which is a wrapper around a library called
DoctrineExtensions. Like a lot of packages, this includes a recipe. But this is
the first recipe that comes from the "contrib" repository. Remember: Symfony
actually has *two* repositories for recipes. There's the main one, which is closely
guarded by the Symfony core team. Then another called `recipes-contrib`.
There *are* some quality checks on that repository, but it's maintained by the
community. The first time that Symfony installs a recipe from the "contrib"
repository, it asks you if that's okay. I'm going to say `p` for "yes permanently".
Then run:

```terminal
git status
```

Awesome! It enabled a bundle and added a new configuration file that we'll look at
in a second.

## Enabling Timestampable

So this bundle obviously has its own documentation. You can search for
`stof/doctrine-extensions-bundle` and find it on Symfony.com. But the *majority*
of the docs live on the underlying DoctrineExtensions *library*... which contains
a bunch of really cool behaviors, including "sluggable" and "timestampable". Let's
add "timestampable" first.

Step one: go into `config/packages/` and open the configuration file
it just added. Here, add `orm` because we're using Doctrine ORM, then `default`, and
lastly `timestampable: true`.

[[[ code('05164f0e85') ]]]

This won't really *do* anything yet. It just activates a Doctrine listener that will
be *looking* for entities that support timestampable each time an entity is
inserted or updated. How do we make our `VinylMix` support timestampable? The
easiest way (and the way I like to do it) is via a trait.

At the top of the class, say `use TimestampableEntity`.

[[[ code('11c47f274d') ]]]

That's *it*. We're done! Lunch break!

To understand this black magic, hold "cmd" or "ctrl" and
click into `TimestampableEntity`. This adds two properties: `createdAt` and
`updatedAt`. And these are just normal fields, like the `createdAt` that we had
before. It also has getter and setter methods down here, just like *we* have in
our entity.

The magic is this `#[Gedmo\Timestampable()]` attribute. This says that:

> this property should be set `on: 'update'`

and

> this property should be set `on: 'create'`.

Thanks to this trait, we get all of this for free! And... we no longer need *our*
`createdAt` property... because it already lives in the trait. So delete the property...
and the constructor... and down here, remove the getter and setter methods.
Cleansing!

## Adding the Migration

The trait has a `createdAt` property like we had before, but it also adds an
`updatedAt` field. And so, we need to create a new migration for that. You know
the drill. At your terminal, run:

```terminal
symfony console make:migration
```

Then... let's go check that file... just to make sure it looks like we expect. Let's
see here... yup! We've got `ALTER TABLE vinyl_mix ADD updated_at`. And apparently
the `created_at` column will be a *little* bit different than we had before.

[[[ code('6ab7d128f7') ]]]

## When Migrations Fail

Okay, let's go run that:

```terminal
symfony console doctrine:migrations:migrate
```

And... it *fails*!

> `[...] column "updated_at" of relation "vinyl_mix" contains null values`.

This is a `Not null violation`... which makes sense. Our database already has a bunch
of records in it... so when we try to add a new `updated_at` column that doesn't
allow null values... it freaks out.

If the current state of our database were already on production, we would need
to tweak this migration to give the new column a default value for those existing
records. *Then* we could change it back to not allowing null. To learn more about
handling failed migrations, check out a
[chapter on our Symfony 5 Doctrine tutorial](https://symfonycasts.com/screencast/symfony5-doctrine/bad-migrations).

But since we do *not* have a production database yet that contains `viny_mix` rows,
we can take a shortcut: drop the database and start over with zero rows. To
do that, run

```terminal
symfony console doctrine:database:drop --force
```

to completely drop our database. And recreate it with

```terminal
symfony console doctrine:database:create
```

At this point, we have an empty database with no tables - even the migrations
table is gone. So we can re-run *all* of our migrations from the very beginning.
Do it:

```terminal
symfony console doctrine:migrations:migrate
```

Sweet! Three migrations were executed: all successfully.

Back over on our site, if we go to "Browse Mixes", it's empty... because we cleared
our database. So let's go to `/mix/new` to create mix ID 1... then refresh a few
more times. Now head to `/mix/7`... and upvote that, which will *update* that
`VinylMix`.

Ok! Let's see if timestampable worked! Check the database by running:

```terminal
symfony console doctrine:query:sql 'SELECT * FROM vinyl_mix WHERE id = 7'
```

And... awesome! The `created_at` is set and then the `updated_at` is
set to just a few seconds later when we upvoted the mix. It *works*. We can now
easily add `timestampable` to *any* new entity in the future, just by adding that
trait.

Next: let's leverage another behavior: sluggable. This will let us create fancier
URLs by automatically saving a URL-safe version of the title to a new property.

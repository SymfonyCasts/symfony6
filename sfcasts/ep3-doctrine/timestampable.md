# Timestampable

I *really* like adding timestampable behavior to my entities. That's where you have `$createdAt` and `$updatedAt` properties that are automatically set. It just helps us keep track of when things happened. We added a `$createdAt` cleverly by hand by sending it in a constructor. But what about `$updatedAt`? Doctrine just happens to have an awesome event system, and we *could* hook into that to automatically run code on update that sets an `$updatedAt` property. *But* there's a library that *already* does that. Let's get it installed.

At your terminal, run:

```terminal
composer require stof/doctrine-extensions-bundle
```

This installs a small bundle, which is a wrapper around a library called DoctrineExtensions. Like a lot of libraries, this installs a repository, but this is the first one we've installed that's from the "contrib" repository. Remember, Symfony actually has *two* repositories for its recipes. There's the main Symfony repositories, which are closely guarded by the Symfony core team. There's also another one called `recipes-contrib`. There are some quality checks on this repository, but it's a lot easier to get recipes in there. The first time that Symfony installs a recipe in the "contrib" repository, it tells you about that and asks you if that's okay. I'm going to say `p` for "yes permanently", and then I'll run

```terminal
get status
```

Awesome! You can see this added a new bundle, as well as a new configuration file that will look at in a second.

So this bundle obviously has its own documentation. You can search for `stof/doctrine-extensions-bundle` and find some documentation on Symfony.com. But the majority of the documentation is actually on the underlying DoctrineExtensions library. This library gives us the ability to add a bunch of really cool behaviors, like "sluggable" and "timestampable". Let's add "timestampable" first.

This first step is to go into `/config/packages` and open the new configuration file it just added. Here, add `orm` because we're using Doctrine ORM, then `default`, and lastly `timestampable: true`. This doesn't *do* anything yet. It just activates a Doctrine listener that will now be looking for entities that support `timestampable` each time an entity is inserted or updated. How do we make our `VinylMix` support `timestampable`? The easiest way (and the way I like to do it) is via a trait.

Af the top of the class, say `use TimestampableEntity`. That's *it*. The real magic is actually inside of this. Hold "cmd" or "ctrl" and click into `TimestampableEntity`. This adds two properties: `createdAt` and `updatedAt`. These are just normal columns, like the `createdAt` that we had before, and it also has getter and setter methods down here, just like we have in our real entity. The real magic here is this `#[Gedmo\Timestampable()]` attribute. This activates that "this property should be set `on: 'update'`" and "this property should be set `on: 'create'`". Thanks to this trait, we don't actually have to add any of that code. We can just use that trait and it will work. We actually no longer need our `createdAt`, because that's already in the trait. So I'll delete the property... and the constructor... and down here, I'll also delete the getter and setter method. All of that is inside of the trait. Cool!

The trait has a `created_at` like the one we had before, but it also adds an `updated_at` column. We need to create a new migration for that. At your terminal, run:

```terminal
symfony console make:migration
```

Awesome! That generated the file. I always like to go check on that file, just to make sure it looks like I expect. Let's see here... awesome! We've got `ALTER TABLE vinyl_mix ADD updated_at`. And then you can see it's actually altering the `created_at` slightly. Beautiful!

Okay, let's go run that:

```terminal
symfony console doctrine:migrations:migrate
```

And... it *fails* - `[...] column "updated_at" of relation "vinyl_mix" contains null values`. This is a `Not null violation`. That makes sense. Our database already has a bunch of records in it, so when we try to add a new `updated_at` column that doesn't allow null values, it freaks out because all existing rows are lacking a value for that. If the current state of our database was already on production, we would need to tweak this migration to give the new column a default value for those existing records. You can learn more about that in our other tutorial. But since that's not the case and we don't have anything on production yet, we can just drop our database and start over without any records. To do that, run

```terminal
symfony console doctrine:database:drop --force
```

to completely drop our database. Then we can recreate the database with:

```terminal
symfony console doctrine:database:create
```

At this point, we have an empty database with no tables, so we can re-run all of our migrations. In this case, because I dropped the database *entirely*, that also dropped that migrations versions table as well, so running

```terminal
symfony console doctrine:migrations:migrate
```

will execute all of our migrations from the very beginning. You can see three migrations were execute, and it worked that time

Back over on our site, if we go to "Browse Mixes", we don't have any mixes at the moment because we just cleared our own database. So let's go to `/mix/new` to create mix ID 1. I'll refresh six more times... and then let's go to `/mix/7`... and upvote that.

All right, let's see if that worked. I'm going to cheat and use

```terminal
symfony console doctrine:query:sql 'SELECT *
FROM vinyl_mix WHERE id = 7'
```

And... awesome! Check this out! The `created_at` is set and then the `updated_at` is set for just a few seconds later when we upvoted the mix. It *works*. We can now easily add `timestampable` to *any* new entity in the future, just by adding that trait.

Next, let's leverage another behavior: `sluggable`. That's going to allow us to have nicer URLs that *don't* have the ID in them.

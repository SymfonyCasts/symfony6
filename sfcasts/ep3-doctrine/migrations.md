# Migrations

We've created our `Entity` class. Yay! But *that's it*. The corresponding table does not yet exist in our database. In theory, Doctrine knows about our entity and all of the columns, so... shouldn't it be able to make that table for us automatically? Yes! It *can*.

When we installed Doctrine earlier, it came with a migrations library that's *amazing*. Check this out! Whenever you make a change to your database structure, like adding a new `Entity` class, or even adding a new property to an *existing* `Entity` class, you can spin over and run:

```terminal
symfony console make:migration
```

In this case, I'm running `symfony console` because this is going to talk to my database. I'll show you. Run that and... perfect! It creates one new file in a `migrations` directory, which has a timestamp for today's date. Let's take a look at that. Find `/migrations` and open this "Version" file here.

This file has `up` and `down` methods. You *can* technically migrate down, but I never do, so let's just focus on the `up`. This is great. The migrations command saw our `vinyl_mix` entity, realized that its table was missing, and generated the SQL needed in Postgres to create it, including all of the columns. That's *super* simple. But how do we actually *execute* this migration? Spin back over and run:

```terminal
symfony console doctrine:migrations:migrate
```

Say `y` for "yes" to confirm and... beautiful! It tells us it's `Migrating up to` that specific version. It seems like that worked! To make sure it did, you can connect to your database however you want, or you can use another `bin console` command:

```terminal
symfony consult doctrine:query:SQL
```

At the end, we can say:

```terminal
'SELECT * FROM vinyl_mix'
```

because, as I mentioned earlier, it should create a `vinyl_mix` table. And when we do... whoops! Pardon my typo. Let me try that again with a single quote at the end and... perfect! We didn't get an error! It just says that `The query yielded an empty result set`. If that table did not exist, like `vinyl_foo` for example, we would have received an error telling us there was an `Undefined table`. So that *did* create the table. Woo!

You might be wondering how the migration system works behind the scenes. If I run

```terminal
symfony console doctrine:migrations:migrate
```

again, it's smart enough to avoid executing this migration again. It knows that it *already* did this. The way it does that becomes a little more obvious if you run:

```terminal
symfony console doctrine:migrations:status
```

This gives some general information about the SQL system. The most important thing here is in `Storage`, where it says `Table Name` and `doctrine_migration_versions`. The first time we executed that migration, Doctrine created this special table, which literally just stores a list of all of the migration classes that it's executed. So every time we run `doctrine:migrations:migrate`, it's going to look in our `/migrations` directory for all of the classes inside of here, but it's only going to execute the *new* ones that it hasn't executed before. Another way to visualize this is to run:

```terminal
symfony console doctrine:migrations:list
```

This gives you a status of all of the migrations. It sees our migration here and it knows it already ran it. It even has the date!

Okay, this is cool, but let's do something a little more interesting. In our `VinylMix` entity, one property I forgot to add was `votes`. We're going to keep track of the number of upvotes or downvotes that a particular mix has. So what we want to do is modify this class to add a new property, and you can actually do that with the same

```terminal
./bin/console make:entity
```

command. This is used to *create* entities, but we can also *update* entities. I'll add `VinylMix` as the class name, and it sees that this already exists. Then we can add a new property: `votes`. I'll make this an `integer` type, say "no" to making it nullable, and then hit "enter" to finish. The end result is that it added this new property, as well as the corresponding getter and setter methods down below.

In this situation, we know that we have a `vinyl_mix` table in the database, but it's going to be missing this new `votes` column. We need to modify the table to add it. How do we do that? The exact same way! At your terminal, run:

```terminal
symfony console make:migration
```

Then go check out that class.

This is amazing! If you look in the `up()` method, it says `ALTER TABLE vinyl_mix ADD votes INT NOT NULL`. So it saw our `VinylMix` entity, glanced at the `vinyl_mix` table in the database, and generated the diff between them. It realized that, in order to make the database look like our entity, it needed to alter the table and add that `votes` column. Doctrine is *smart*.

Now, over in our terminal, if you run

```terminal
symfony console doctrine:migrations:list
```

you'll see that it recognizes *both* migrations and it knows that it hasn't migrated the second one. To do that, run:

```terminal
symfony console doctrine:migrations:migrate
```

Docrine is smart enough to *skip* the first one and *execute* the second one. Nice! This means each time you deploy, you should run this `doctrine:migrations:migrate` command, and it will handle executing any and all migrations that the production database hasn't executed yet.

There's one more quick thing that I want to do while we're here. Inside of our `VinylMix` entity, you can see the new `votes` property that currently defaults to `null`. With a new vinyl mix, it would make a lot of sense to default its votes to *zero*. So I'm going to change this `null` to `0`. If I do that, I don't need to make this property nullable anymore because it has nothing to do with Doctrine, so we can remove this `?`. If I have a property that's initialized to an integer, then it's going to be an `int` at all times. It's never going to be null.

But now I wonder... because I made that change, do I need to alter anything in my database? The answer is *no*. And how we know that is by running a helpful command called

```terminal
symfony console doctrine:schema:update --dump-sql
```

This is very similar to the `make:migration` command, but instead of generating a file with the SQL, it will just *dump* whatever SQL is needed to bring your database up to date. And in this case, it can show you that your database is *already* in sync with your entity. So if you initialize the value in PHP, this is just a PHP change. It doesn't change how the column is stored in the database.

Let's actually make one other change. We have this `$createdAt` field here. It would be *awesome* if that would be set *automatically* when we create a new `vinyl_mix` object, instead of having to do it manually. We can do that by creating a good, old-fashioned PHP `__construct()` method. Inside of here, I'll say `$this->createdAt = new \DateTimeImmutable()`, which, of course, defaults to *right now*. And we don't need the `= null` up here anymore, since it's going to be initialized down here. We also don't need the `?`, because it's always going to be a `DateTimeImmutable` object. Nice! That's going to set our `$createdAt` property automatically *every time* we instantiate our object. That's just a PHP change, so it doesn't change how data is stored in the table at all.

All right, we have a `VinylMix` entity, and we have the corresponding table in the database. Next, let's instantiate a `vinyl_mix` object and *save* it to the database.

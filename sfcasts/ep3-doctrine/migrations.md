# Migrations

We created an entity class! But... *that's it*. The corresponding table does
not *yet* exist in our database.

Let's think. In theory, Doctrine knows about our entity, all of its properties
and their `ORM\Column` attributes. So... shouldn't Doctrine be able to make that
table *for* us automatically? Yes! It *can*.

## The make:migration Command

When we installed Doctrine earlier, it came with a migrations library that's
*amazing*. Check it out! Whenever you make a change to your database structure -
like adding a new entity class, or even adding a new property to an *existing*
entity, you should spin over to your terminal and run:

```terminal
symfony console make:migration
```

In this case, I'm running `symfony console` because this *is* going to talk to our
database. Run that and... perfect! It created one new file in a `migrations/`
directory with a timestamp for today's date. Let's go check it out!
Find `migrations/` and open the new file.

[[[ code('2a34551803') ]]]

This holds a class with `up()` and `down()` methods... though I never run migrations
in the "down" direction, so we'll focus only on `up()`. And... this is great! The
migrations command saw our `VinylMix` entity, *realized* that its table was missing
in the database, and generated the SQL needed in Postgres to create it, including
all of the columns. That was *so* easy.

## Executing the Migration

Ok... so how do we *execute* this migration? Back at your terminal, run:

```terminal
symfony console doctrine:migrations:migrate
```

Say `y` to confirm and... beautiful! It tells us that it's `Migrating up to`
that specific version. It seems... like that worked! To make sure, you can
try another `bin/console` command: `symfony console doctrine:query:sql`
with `SELECT * FROM vinyl_mix`.

```terminal-silent
symfony console doctrine:query:sql 'SELECT * FROM vinyl_mix'
```

When we try that... whoops! Pardon my typo... nothing to see here. Try that again
and... perfect! We didn't get an error! It just says that
`The query yielded an empty result set`. If that table did *not* exist, like
`vinyl_foo`, Doctrine would have *screamed* at us.

So, the migration *did* run!

## How Migrations Work

This beautiful system deserves some explanation. Run

```terminal
symfony console doctrine:migrations:migrate
```

again. Check it out! It's smart enough to *avoid* executing that migration a second
time! It *knows* that it already did that. But... how? Try running a different command:

```terminal
symfony console doctrine:migrations:status
```

This gives some general info about the migration system. The most important part
is in `Storage` where it says `Table Name` and `doctrine_migration_versions`.

Here's the deal: the first time we executed the migration, Doctrine *created* this
special table, which literally stores a list of all of the migration classes
that *have* been executed. Then, each time we run `doctrine:migrations:migrate`,
it looks in our `migrations/` directory, finds all the classes, checks the database
to see which have *not* already been executed, and only calls those.
Once the new migrations finish, it adds them as rows to the `doctrine_migration_versions`
table.

You can visualize this table by running:

```terminal
symfony console doctrine:migrations:list
```

It sees our *one* migration and knows it already ran it. It even has the date!

This is cool... but let's push it further. Next, let's add a new property to
our entity and generate a *second* migration to add the column.

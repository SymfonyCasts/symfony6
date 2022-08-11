# Adding new Properties

In our `VinylMix` entity, I forgot to add a property earlier: `votes`. We're going
to keep track of the number of up votes or down votes that a particular mix has.

## Modifying with make:entity

Ok... so how can we add a *new* property to an entity? Well, we can *absolutely*
do it by hand: all we need to do is add the property and the getter and setter
method. *But*, a much easier way is to head *back* to our favorite `make:entity`
command

```termina-silent
./bin/console make:entity
```

This is used to *create* entities, but we can also use it to *update* an entity.
Type `VinylMix` as the class name and... it sees that it already exists! Add
a new property: `votes`... make it an `integer`, say "no" to making it nullable..
then hit "enter" to finish.

The end result? Our class has a new property... and getter and setter methods below.

## Generating a Second Migration

Ok, let's think. We have a `vinyl_mix` table in the database... but does *not*
yet have the new `votes` column. We need *alter* the table to add it. How can
we do that? The exact same way: a migration! At your terminal, run:

```terminal
symfony console make:migration
```

Then go check out the new class.

This is amazing! Inside the `up()` method, it says

> ALTER TABLE vinyl_mix ADD votes INT NOT NULL

So it saw our `VinylMix` entity, checked out the `vinyl_mix` table in the database,
and generated a *diff* between them. It realized that, in order to make the database
look like our entity, it needed to alter the table and add that `votes` column.
That is *amazing*.

Back over at the terminal, if you run

```terminal
symfony console doctrine:migrations:list
```

you'll see that it recognizes *both* migrations and it knows that it has *not*
executed the second one. To do that, run:

```terminal
symfony console doctrine:migrations:migrate
```

Doctrine is smart enough to *skip* the first and execute the *second*. Nice!


When you deploy to production, all you need to do is run `doctrine:migrations:migrate`
each time, and it will handle executing any and all migrations that the *production*
database hasn't yet executed.

## Giving Properties Default Values

Ok, one more quick thing that I want to do while we're here. Inside of `VinylMix`,
the new `votes` property defaults to `null`. But, when we create a new `VinylMix`,
it would make a lot of sense to default the votes to *zero*. So let's change this
to `0`.

Cool! And if we do that, the property in PHP no longer needs to allow `null`...
so remove the `?`. Since we're initializing to an integer, then this property will
*always* be an `int`: it will never be null.

But... I wonder... because I made this change, do I need to alter anything in my
database? The answer is *no*. I can prove it by running a helpful command:

```terminal
symfony console doctrine:schema:update --dump-sql
```

This is very similar to the `make:migration` command... but instead of generating
a file with the SQL, it will just print out whatever SQL is needed to bring your
database up to date. In this case, it shows that our database is *already* in sync
with our entity.

The point is: if we initialize the value of a property in PHP... that's *just* a
PHP change. It doesn't change the column in the database.

## Auto-Setting createdAt

Let's initialize one other field: `$createdAt`. It would be *amazing* if something
automatically set this property whenever we created a new `VinylMix` object... instead
of us needing to set it manually.

Whelp, we can do that by creating a good, old-fashioned PHP `__construct()` method.
Inside, say `$this->createdAt = new \DateTimeImmutable()`, which will default to
*right now*.

That's it! And, like with `votes`, we don't need the `= null` anymore since it
will be initialized down here... and we also don't need the `?`, because it will
*always* be a `DateTimeImmutable` object.

Nice! Thanks to this, the `$createdAt` property will automatically be set *every
time* we instantiate our object. And that's just a PHP change: it doesn't change
the column in the database.

All right, we have a `VinylMix` entity *and*  the corresponding table. Next, let's
instantiate a `VinylMix` object and *save* it to the database.

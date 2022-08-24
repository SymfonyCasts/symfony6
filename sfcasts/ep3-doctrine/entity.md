# Entity Class

One of the coolest, but maybe most *surprising* things about Doctrine, is that it
wants you to pretend like the database doesn't exist! Yea, instead of thinking
about tables and columns, Doctrine wants us to think about objects and properties.

For example, let's say that we want to save some product data. The way we do that
with Doctrine is by creating a `Product` class with *properties* that hold the data.
Then you instantiate a `Product` object, set data onto it and politely ask Doctrine
to save it for you. *We* don't have to worry about *how* Doctrine does that.

But, of course, behind the scenes Doctrine *is* talking to a database. It will
INSERT the data from the `Product` object into a `product` table where each property
is mapped to a column. This is called an Object Relational Mapper, or *ORM*.

Later, when we want to get that data back, we don't think about "querying" that table
and its columns. Nope, we simply ask Doctrine to find the object that we had earlier.
Of course, it *will* query the table... then recreate the object with the data.
But that's not a detail *we* think about: we ask for the `Product` object, and it
gives it to us. Doctrine handles all of the saving and querying *automatically*.

## Generating the Entity with make:entity

*Anyways*, when we use an ORM like Doctrine, if we want to save something to
the database, we need to create a class that *models* the thing we want to save, like
a `Product` class. In Doctrine, these classes are given a special name: *entities*.
Though, they're really just normal PHP classes. And while you *can* create these
entity classes by hand, there's a MakerBundle command that makes life *much* nicer.

Spin over to your terminal and run:

```terminal
php bin/console make:entity
```

In this case, we don't have to run `symfony console make:entity` because this
command will *not* talk to the database: it *just* generates code. But, if you're
ever not sure, using `symfony console` is always safe.

Okay, we want to create a class to store all of the vinyl mixes in our system. So
let's create a new class called `VinylMix`. Then answer `no` for broadcasting
entity updates: that's an extra feature related to Symfony Turbo.

Ok, here's the important part: it asks which properties we want. We're going
to add *several*. Start with one called `title`. Next it asks which *type* this
field is. Hit `?` to see the full list.

These are *Doctrine* types... and each one will map to a different column type
in your database, depending on which database you're using, like MySQL or
Postgres. The basic types are on top like `string`, `text` - which can hold
*more* than a string) - `boolean`, `integer` and `float`. Then relationship fields -
we'll talk about those in the next tutorial - some special fields, like storing
JSON and date fields.

For `title`, use `string`, which can hold up to 255 characters. I'll keep the default
length... then it asks us if the field can be null in the database. I'll answer `no`.
This means that the column *cannot* be null. In other words, the column will be
*required* in the database.

And... one field done! Let's add a few more. We need a `description`, and make this
a `text` type. `string` maxes out at 255 characters, `text` can hold a ton more.
This time, I'll say `yes` to making it nullable. So this will be an *optional*
column in the database. Another one down!

For the next property, call it `trackCount`. It will be an `integer` and will
be *not* null. Then add `genre`, as a `string`, length 255... and also not null
so that it's required in the database.

*Finally*, add a `createdAt` field so we can know when each vinyl mix was
originally created. This time, because the field name ends in "At", the command
suggests a `datetime_immutable` type. Hit "enter" to use that, and also make this
*not* null in the database.

We don't need to add any more properties right now so hit "enter" one more time to
exit the command.

Done! What did this do? Well first, I can tell you that this did *not* talk to
or change our database at *all*. Nope, it simply generated two classes. The first
is `src/Entity/VinylMix.php`. The *second* is `src/Repository/VinylMixRepository.php`.
Ignore the `Repository` one for now... we'll talk about its purpose in a few minutes.

[[[ code('33b9a4adde') ]]]

## Checking out the Entity Class & Attributes

Go open up the `VinylMix.php` entity. Say hello to... a... wow, pretty normal, boring
PHP class! It generated a `private` property for each field we added,
plus an extra `id` property. The command also added a getter and setter method for
each of these. So... this is basically just a class that holds data... and we can access
and set that data via the getter and setter methods

The *only* thing that makes this class special are the attributes. The `ORM\Entity`
above the class tells Doctrine:

> Hey! I want to be able to save objects of this class to the database. This
> is an *entity*.

Then, above each property, we use `ORM\Column` to tell Doctrine that we want to
save this property as a *column* in the table. This also communicates other options
like the *length* of the column and whether or not it should be *nullable*.
`nullable: false` is the default... so the command only generated `nullable: true`
on the *one* property that needs it.

The other thing `ORM\Column` controls is the field *type*. That's set via this `type`
option. As I mentioned, this doesn't refer directly to a MySQL or Postgres type...
its a *Doctrine* type that will then *map* to something specific based on our
database.

## Field Type Guessing

But, interesting: the `type` option only shows up on the `$description` field.
The reason for that is *really* cool... and new! Doctrine is smart. It looks at the
type on your *property* and *guesses* the field type from that. So when you have
a `string` property type, Doctrine assumes that you want that to be *its* `string`
type. You *could* write `Types::STRING` inside `ORM\Column`... but that would be
totally redundant.

We *do* need it for the `description` field, however... because we want to use the
`TEXT` type, *not* the `STRING` type. But in every *other* situation, it works.
Doctrine guesses the correct type from the `?int` property type... and the same thing
happens down here for the `?\DateTimeImmutable` type.

## Table and Column Naming

In addition to controlling things about each column, we can *also* control the *name*
of the table by adding an `ORM\Table` above the class with name set to, for example,
`vinyl_mix`. But, *surprise*! We don't need to do that! Why? Because Doctrine is
really good at generating great names. It generates the table name by transforming
the class into snake case. So even *without* `ORM\Table`, this will be the name
of the table. The same applies to properties. `$trackCount` will map to a
`track_count` column. Doctrine handles all of this for us: we don't need to
think about our table or column names at all.

At this point, we've run `make:entity` and it generated an entity class for us. Yay!
But... we don't actually *have* a `vinyl_mix` table in our database yet. How do we
create one? With the magic of *database migrations*. That's next.

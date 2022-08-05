# Entity Class

One of the coolest, but maybe most *surprising* things about Doctrine is that it basically wants you to pretend like the database doesn't exist. Instead of thinking about tables and columns, all we need to do is think about objects and properties. For example, let's say that we want to save some product data. The way you do that in Doctrine is by creating a product class with some properties that hold the data. Then you create that property object, set the data onto it, and then politely ask Doctrine to save that for you. We don't have to worry about *how* Doctrine does that. Behind the scenes, however, Doctrine will save that object to a table where each property is mapped to a column. This is called the Object Relational Mapper, or *ORM*, which literally means that it maps the data on an object to a row on a table.

Later, when you want to get that data back, you don't need to query that table and its columns. Instead, you just ask Doctrine to find the object that you had earlier. What it's *actually* doing is querying that table to get all of the data and then recreates and gives us that data object. So all *we* have to do is deal with classes and objects, and Doctrine takes care of all of the saving and querying *automatically*.

This means that if we ever need to save something to the database with Doctrine, we need to create a special class to *model* it, like a product class. In Doctrine, these classes are given a special name: *Entities*. They're really just normal PHP classes, and while you *can* create them by hand, there's a MakerBundle command that makes this *much* simpler.

Spin over to your terminal and run:

```terminal
./bin/console make:entity
```

In this case, we don't have to run

```terminal
symfony console make:entity
```

because this command doesn't need to talk to the database. It *just* generates code.

Okay, we want to create a class where we're able to save all of the vinyl mixes in our system, so let's create a new class called `VinylMix`, and then click `no` for broadcasting entity updates. That's an extra thing related to Symfony Turbo. Then it asks which properties we want, so we can interactively start adding properties to our class. We're going to add *several*, and I'll start with one called `title`.

Next, it's going to ask for the field type. This is referring to a Doctrine type and you can hit `?` to show you all of the different Doctrine types. You have the basic types up here, like `string` and `text` (which can hold *more* than a string), `boolean`, `integer`, `float`, and their relationship fields. We'll talk about those in a future tutorial. You also have some special fields here for things like storing JSON or the `DateTime`.

In our case, I'm going to use a `string` field, which can hold up to 255 characters, and I'll keep the default length. Then it asks us if the field can be null in the database. I'll answer `no`, which means it will be *not* null, or *required* in the database. And... done!

We can add as many properties like this as we want, so let's add a few more. We need a description, so let's make this a `text` type, which can store more text than the string. This time, I'll say `yes` to making it nullable, which will make this an *optional* field in the database. Another one down!

For the next property, let's call it `trackCount`. This will be an `integer` type and we will have this be *not* null. I'll also add `genre`, which will be a `string` type with a length of 255 characters, and I'll also set it to *not* null so that it's required in the database.

*Finally*, let's add a `createdAt` field so we'll know when these vinyl mixes were originally created. This time, because the field name, which ends in "At", sounds like a date field, the command suggests it be a `datetime_immutable`. I'll hit "enter" to use that, and I'll also make this *not* null in the database. We don't have any more properties to add right now, but we can add more later if needed. Hit "enter" one more time to exit the command.

To be clear, this didn't talk to or interact with our database *at all*. All this command did was generate two classes. The first is `src/Entity/VinylMix.php`. The *second* is `src/Repository/VinylMixRepository.php`. Ignore the `/Repository` one for now. We'll talk about that in a few minutes.

Go open up the `VinylMix.php` entity. For the most part, this is a normal, boring PHP class. You can see that it created a `private` property for all of the different fields that we added, including an extra `private ?int $id` property. It also added a getter and setter method for each of those private properties. So this is basically just a class that holds data, and we can access and set that data via the getter and setter methods.

The only thing that makes this class special *at all* are these annotations. This `ORM\Entity` above the class tells Doctrine

`Hey! I want to be able to save the objects of
this class to the database. This is an *entity*.`

Then, above each property, we have `ORM\Column`, which tells Doctrine that we want to save this property as a column in the table. This also communicates other things, like the *length* that the column should be and whether or not it should be nullable in the database (`nullable: false` is the default). You can see that it only generated `nullable: true` on a single column where we needed that value.

The other thing `ORM\Column` controls is the *type* of field that that will be in the database, like whether it's a `string` field, a `datetime` field, a `blob` field, etc. And *that's* controlled via this `type` option. This doesn't refer directly to a MySQL or Postgres type. It's more like a Doctrine typing system. There's a `TEXT` *type* in Doctrine.

Notice that this `type` option only shows up on the `$description` field. The reason for that is really cool. Doctrine is *smart*. It's able to look at the type on your property and *guess* the type from it. So when you have a `STRING` property type, Doctrine assumes that you want that to be its string type. You *could* put this `Types: STRING` up here in `ORM\Column`, but it's not needed because Doctrine is *already* guessing that. We *do* need it below here though, because in this case, we want to use the `TEXT` type, *not* the `STRING` type. But in every *other* situation, it works. Doctrine guesses the correct type from this `?int` property type, and the same thing happens down here for the `?\DateTimeImmutable`, which tells it that it should be a `datetime_immutable` type. So, for the most part, Doctrine  is *really* good at figuring out the type automatically.

In addition to controlling things about each column, we can *also* control the *name* of this table if we want to, by adding an `ORM\Table` above this with the name set to, for example, `vinyl_mix`. But, *surprise*! We don't even need to do that, because Doctrine is really good at guessing that by default *too*. It takes your class names and turns them into lower camel case. So even *without* this `ORM\Table`, that will be the name that this table saves to. The same thing applies to your property `$trackCount` here, which will end up being `track_count`. This is *awesome*. It just takes care of all of that for you, and you don't need to think about your table or column names at all.

At this point, we've run that command and it generated this class for us. Yay! But we don't actually *have* a `vinyl_mix` table in our database with all of these columns yet. How do we create one? With a *database migration*. That's next.

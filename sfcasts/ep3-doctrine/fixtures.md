# Simple Doctrine Data Fixtures

"Data fixtures" is the name given to dummy data that you add to your app while
developing or running tests to make life easier. It's a lot easier to work on a new
feature when you actually *have* decent data in your database. We created some
data fixtures, in a sense, via this `new` action. But Doctrine has a system
specifically for this.

# Installing DoctrineFixturesBundle

Search for "doctrinefixturesbundle" to find its GitHub repository. And you can
actually read its documentation over on Symfony.com. Copy the install line and,
at your terminal, run it:

```terminal
composer require --dev orm-fixtures
```

Of course, `orm-fixtures` is an alias, in this case, to
`doctrine/doctrine-fixtures-bundle`. And... perfect! Run

```terminal
git status
```

to see that this added a bundle, as well as a new `src/DataFixtures/` directory.
Go open this up. Inside, we have a single new file called `AppFixtures.php`.

DoctrineFixturesBundle is a delightfully simple bundle. It gives us a new console
command called `doctrine:fixtures:load`. When we run this, it will empty our database
and then execute the `load()` method inside of `AppFixtures`. Well, it will actually
execute the `load()` method on *any* service we have that extends this `Fixture`
class. So we *could* have multiple classes in this directory if we want.

Check it out: if we run it right now... with an empty `load()` method, it empties
our database, calls that empty method, and... the result over on the "Browse" page
is that we have nothing!

```terminal-silent
php bin/console doctrine:fixtures:load
```

## Filling in the load() Method

That's not very interesting, so let's go fill in that `load()` method! Start in
`MixController`: steal all of the `VinylMix` code... and paste it here. Hit "Ok"
to add the `use` statement.

Notice the `load()` method accepts some `ObjectManager` argument. That's actually
the `EntityManager`, since we're using the ORM. If you look down here, it already
has the `flush()` call. The only thing we're missing is the `persist()` calls:
`$manager->persist($mix)`.

So the variable is *called* `$manager` here... but these two lines are *exactly*
what we have our controller: `persist()` and `flush()`.

Try the command again.

```terminal-silent
php bin/console doctrine:fixtures:load
```

It empties the database, executes our fixtures, and we have... *one* new mix!

Okay, this is *kind of* cool. We have a new `bin/console` command to load stuff.
But for developing, I want a really *rich* set of data fixtures, like... maybe 25
mixes. We *could* add those by hand here... or even create a loop. But there's a
better way, via a library called "Foundry". Let's explore it next!

# Simple Doctrine Data Fixtures

"Data fixtures" is the name given to dummy data that you add to your app while
developing or running tests to make life easier. It's a lot nicer to work on a new
feature when you actually *have* decent data in your database. We created some
data fixtures, in a sense, via this `new` action. But Doctrine has a system
specifically designed for this.

## Installing DoctrineFixturesBundle

Search for "doctrinefixturesbundle" to find its GitHub repository. And you can
actually read its documentation over on Symfony.com. Copy the install line and,
at your terminal, run it:

```terminal
composer require --dev orm-fixtures
```

`orm-fixtures` is, of course, a Flex alias, in this case, to
`doctrine/doctrine-fixtures-bundle`. And... done! Run

```terminal
git status
```

to see that this added a bundle, as well as a new `src/DataFixtures/` directory.
Go open that up. Inside, we have a single new file called `AppFixtures.php`.

[[[ code('766a29102b') ]]]

DoctrineFixturesBundle is a delightfully simple bundle. It gives us a new console
command called `doctrine:fixtures:load`. When we run this, it will empty our database
and then execute the `load()` method inside of `AppFixtures`. Well, it will actually
execute the `load()` method on *any* service we have that extends this `Fixture`
class. So we *could* have multiple classes in this directory if we want.

If we run it right now... with an empty `load()` method, it clears
our database, calls that blank method, and... the result over on the "Browse" page
is that we have nothing!

```terminal-silent
php bin/console doctrine:fixtures:load
```

## Filling in the load() Method

That's not very interesting, so let's go fill in that `load()` method! Start in
`MixController`: steal all of the `VinylMix` code... and paste it here. Hit "Ok"
to add the `use` statement.

[[[ code('76e06fff28') ]]]

Notice the `load()` method accepts some `ObjectManager` argument. That's actually
the `EntityManager`, since we're using the ORM. If you look down here, it already
has the `flush()` call. The only thing we're missing is the `persist()` call:
`$manager->persist($mix)`.

[[[ code('fb84406a7e') ]]]

So the variable is *called* `$manager` here... but these two lines are *exactly*
what we have our controller: `persist()` and `flush()`.

Try the command again:

```terminal
php bin/console doctrine:fixtures:load
```

It empties the database, executes our fixtures, and we have... *one* new mix!

Okay, this is *kind of* cool. We have a new `bin/console` command to load stuff.
But for developing, I want a really *rich* set of data fixtures, like... maybe 25
mixes. We *could* add those by hand here... or even create a loop. But there's a
better way, via a library called "Foundry". Let's explore it next!

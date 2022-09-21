# Fixtures

"Data fixture" is the name given to dummy data that you add into your app while developing or running tests to make life easier. It's a lot easier to work on a new feature when you actually have a data in your database. We've created some data fixtures, in a sense, via this `new` action. But Doctrine has a system specifically for this.

Search for "doctrinefixturesbundle" to find its GitHub repository. And you can actually read its documentation over on Symfony.com. Copy this

```terminal
composer require --dev orm-fixtures
```

line and, at your terminal, run it. Of course, `orm-fixtures` is an alias, in this case, to `doctrine/doctrine-fixtures-bundle`. And... perfect! Let's finish this. Run

```terminal
git status
```

and we can see that this added a bundle, as well as a new `src/DataFixtures/` directory. Let's open this up and, inside, we have a single file called `AppFixtures.php`.

This is a very simple bundle. It gives us a brand new console command called `doctrine:fixtures:load`. When we run this, it will empty our database and then execute the `load()` method inside of `AppFixtures.php`. It will actually execute the `load()` method on *any* service we have that extends this `Fixture` class. So we *could* have multiple fixtures in this directory if we wanted to. Check it out. If I run it right now... it's empty in there. It empties our database, loads `AppFixtures.php`, and over
on the "Browse" page, we have *nothing*.

All right, let's go steal our `VinylMix()` code from `MixController.php`. I'll copy that... and we'll paste it here. I'll hit "OK" to add the `use` statement. Notice the `load()` method passes something called the `ObjectManager`. That's actually the `EntityManager`, since we're using the ORM. If you look down here, it already has the `flush()` that we expect to see. The only thing we need to have is `$manager->persist($mix)`. So it's *called* `$manager` here, but these two lines are *exactly* what we had over in our controller: `persist()` and `flush()`. If we go try that command again, it empties the database, executes our fixtures, and we have... *one* new mix!

Okay, this is *kind of* cool. We have that new `./bin/console` command, which helps out. But for developing, I want a really rich set of data fixtures, like... maybe 25 mixes. We *could* add those by hand here or even add a loop to create them. But there's a better way, via a library called "Foundry". Let's explore that next.

# Foundry

Building fixtures is pretty simple, but *kind of* boring. It would be *super* boring to create *25* mixes inside this load method. That's why we're going to install an awesome library called "Foundry". To do that, run:

```terminal
composer require zenstruck/foundry --dev
```

We're using `--dev` because we only need this tool when we're developing or running tests. When this finishes, you can run

```terminal
git status
```

to see that this enabled the bundle and also created a one configuration file, which we won't need to look at.

Foundry helps us create objects, but to really understand how it works, let's jump in and use it. First, for each entity in your project (right now, we only have one), you'll need a corresponding factory class. You can create that by running

```terminal
./bin/console make:factory
```

which is a Maker command that comes from the Foundry library. Then, you can select which entity class you want to create it for, *or* you can select for *all* of them if you have multiple entities. We'll generate the factory for `VinylMix`... and that created a single file: `VinylMixFactory.php`. Let's go check that out, in `/src/Factory/VinylMixFactory.php`.

Above this class, you can see a bunch of methods being described. This class is really good at creating and saving `VinylMix` objects, *or* creating *many* of them, *or* finding a *random* one, *or* a random *set*, *or* a random *range*. So the only thing that's really inside of here is this `getDefaults(): array`, which returns some default data when a `VinylMix` is created. We'll talk more about this in a second.

To use this class, in `AppFixtures.php`, I'm going to delete *everything* inside of here. Now we can say `VinylMixFactory` and call a static method on it: `createOne()`. That's it! Spin over, reload our fixtures with

```terminal
symfony console doctrine:fixtures:load
```

and... it *fails*.

`Expected argument type "DateTime", "null" given
at property path "createdAt"`

It's telling us that something tried to call `setCreatedAt()` on `VinylMix`, and instead of passing it the `DateTime`, it passed it `null`. What's happening here is this: Inside of our `VinylMix` entity, if you scroll up and open `TimestampableEntity`, we have a `setCreatedAt()` method in here that expects a `DateTime` object. Something called this method and passed it `null` instead.

This actually helps show off how Foundry works. When we call `VinylMixFactory::createOne()`, it creates a new `VinylMix` and then sets all of this data onto it. Remember, all of these properties are *private*. So it doesn't set the title property directly. It calls the `setTitle()` method and the `setTrackCount()` method. Down here, for `createdAt()` and `updatedAt()`, it called `setCreatedAt()` and passed it `null`. In reality, we don't actually *need* to set those two properties at all because they're automatically set. If we try this now... it *works*. And if we go check out our site... *awesome*. You can see it has 928,000 tracks, a random title, and it has 301 votes. All of this is coming from the `getDefaults()` method.

Foundry leverages *another* library called "Faker", whose only job is to help create *fake* data. So if you want some fake text, you could say `self::faker()->`, followed by whatever you want to generate. There are *many* different methods that you can call on `faker()` to get all kinds of fake data. Super handy!

This did a pretty good job, but let's customize it to make it a little more realistic. *Before* we customize it, having *one* vinyl mix still isn't very useful, so instead, inside `AppFixtures`, change this to `createMany(25)`. This is where that library shines. If we reload our fixtures now, with a *single* line of code, we now have *25* random fixtures to work with. The random data *could* be a little bit better, so let's modify that.

Inside `VinylMixFactory.php`, let's change the title. Instead of being `text()`, which can sometimes appear as a *wall* of text, let's change it to `words()`. And then we'll use... let's say... *5* words, and then we'll say *true* so it returns this as a string. Otherwise, the `words()` method returns an array. For `trackCount`, we *do* want a random number, but it would be to have a number between 5 and 20. That seems like a good track count. For the `genre`, let's go for a `randomElement()` and pass this `pop` and `rock`. Those are the two genres that we have been working with in our app so far. Whoops... and make sure you call this like a function. There we go. And let's do the same thing for `votes`. It would be better to have a number between -50 and 50, since we can't have negative votes. Much better!

You can see it added a bunch of our properties here by default, but didn't actually add *all* of them. One property that's missing here is `description`, so we'll say `'description' => self::faker()->` and then add a method here called `paragraph()`. Finally, down here for `slug`, we don't need that *at all* because that's going to be set automatically. If we go over and refresh... super cool! That looks *much* better. You can see we have one broken image here, just because the API I'm using has some missing images on it, but it works just fine.

The Foundry library can do a *ton* of other cool things too, so definitely check out its documentation. It's also super useful when you write tests, and it works *great* with database relations, so you'll see us use it again in a more complex way in the next tutorial.

Next, let's add pagination to this page, because eventually, we won't be able list everything all at once.

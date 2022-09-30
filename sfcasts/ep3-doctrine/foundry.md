# Foundry: Fixtures You'll Love

Building fixtures is pretty simple, but *kind of* boring. And it would be *super*
boring to manually create *25* mixes inside the `load()` method. That's why we're
going to install an awesome library called "Foundry". To do that, run:

```terminal
composer require zenstruck/foundry --dev
```

We're using `--dev` because we only need this tool when we're developing or running
tests. When this finishes, run

```terminal
git status
```

to see that the recipe enabled a bundle and also created one config file... which
we won't need to look at.

## Factories: make:factory

In short, Foundry helps us create entity objects. It's... almost easier just to
see it in action. First, for each entity in your project (right now, we only have
one), you'll need a corresponding *factory* class. Create that by running

```terminal
php bin/console make:factory
```

which is a Maker command that comes from Foundry. Then, you can select
which entity you want to create a factory for... or generate a factory for
*all* your entities. We'll generate one for `VinylMix`. And... that
created a single file: `VinylMixFactory.php`. Let's go check it out:
`src/Factory/VinylMixFactory.php`.

[[[ code('f4cf88fd24') ]]]

Cool! Above the class, you can see a bunch of methods being described... which
will help our editor know what super-powers this has. This factory is really good
at creating and saving `VinylMix` objects... *or* creating *many* of them, *or*
finding a *random* one, *or* a random *set*, *or* a random *range*. Phew!

## getDefaults()

The only important code that we see inside this class is `getDefaults()`,
which returns default data that should be used for each property when a `VinylMix`
is created. We'll talk more about that in a minute.

But first... let's run blindly forward and *use* this class! In `AppFixtures`,
delete *everything* and replace it with `VinylMixFactory::createOne()`.

[[[ code('b572b3c087') ]]]

That's it! Spin over and reload the fixtures with:

```terminal
symfony console doctrine:fixtures:load
```

And... it *fails*! Boo

> Expected argument type "DateTime", "null" given at property path "createdAt"

It's telling us that *something* tried to call `setCreatedAt()` on `VinylMix`...
but instead of passing a `DateTime` object, it passed `null`. Hmm. Inside of
`VinylMix`, if you scroll up and open `TimestampableEntity`, yup! We have a
`setCreatedAt()` method that expects a `DateTime` object. Something called this...
but passed `null`.

This actually helps show off how Foundry works. When we call
`VinylMixFactory::createOne()`, it creates a new `VinylMix` and then sets all of
this data onto it. But remember, all of these properties are *private*. So it doesn't
set the title property directly. Instead, it calls `setTitle()` and `setTrackCount()`
Down here, for `createdAt` and `updatedAt`, it called `setCreatedAt()`
and passed it `null`.

In reality, we don't *need* to set these two properties because they will be
set automatically by the timestampable behavior.

If we try this now...

```terminal-silent
symfony console doctrine:fixtures:load
```

It *works*! And if we go check out our site... *awesome*. This mix has 928,000
tracks, a random title, and 301 votes. All of this is coming from the `getDefaults()`
method.

## Fake Data with Faker

To generate interesting data, Foundry leverages *another* library called "Faker",
whose only job is to... create *fake* data. So if you want some fake text, you
can say `self::faker()->`, followed by whatever you want to generate. There are
*many* different methods you can call on `faker()` to get all *kinds* of fun fake
data. Super handy!

## Creating Many Objects

Our factory did a *pretty* good job... but let's customize things to make it a
bit more realistic. Actually, first, having *one* `VinylMix` still isn't very useful.
So instead, inside `AppFixtures`, change this to `createMany(25)`.

[[[ code('68aa0810e8') ]]]

*This* is where Foundry shines. If we reload our fixtures now:

```terminal-silent
symfony console doctrine:fixtures:load
```

With a *single* line of code, we have *25* random fixtures to work with! Though,
the random data *could* be a bit better... so let's improve that.

## Customizing getDefaults()

Inside `VinylMixFactory`, change the title. Instead of `text()` - which can
sometimes be a *wall* of text, change to `words()`... and let's use *5* words, and
pass *true* so it returns this as a string. Otherwise, the `words()` method
returns an array. For `trackCount`, we *do* want a random number, but... probably
a number between 5 and 20. For `genre`, let's go for a `randomElement()` to
randomly choose either `pop` or `rock`. Those are the two genres that we've been
working with so far. And, whoops... make sure you call this like a function.
There we go. Finally, for `votes`, choose a random number between -50 and 50.

[[[ code('8aa7ee7c72') ]]]

Much better! Oh, and you can see that `make:factory` added a *bunch* of our properties
here by default, but it didn't add *all* of them. One that's missing is
`description`. Add it: `'description' => self::faker()->` and then use `paragraph()`.
Finally, for `slug`, we don't need that *at all* because it will be set automatically.

[[[ code('5a3fff72f5') ]]]

Phew! Let's try this! Reload the fixtures:

```terminal-silent
symfony console doctrine:fixtures:load
```

Then head over and refresh. That looks *so* much better. We *do* have one broken
image... but that's just because the API I'm using has some "gaps" in it...
nothing to worry about.

Foundry can do a *ton* of other cool things, so *definitely* check out
its docs. It's especially useful when writing tests, and it works *great*
with database relations. So we'll see it again in a more complex way in the
next tutorial.

Next, let's add pagination! Because eventually, we won't be able to list
*every* mix in our database all at once.

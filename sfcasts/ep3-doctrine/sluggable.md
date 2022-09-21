# Sluggable

Using a database ID in your URL is kind of lame. You typically see *slugs* instead. These are URL-safe versions of the name of the item. In this case, that's the name of our mix. To support this, all our `VinylMix` entity needs is a slug property that holds that URL-safe string. Then, it's super easy to query for it. The only trick is that something needs to look at the mix's title and set that slug property whenever a mix is saved. That's the job of the `sluggable` behavior.

Go back to `/config/packages/stof_document_extensions.yaml` and add `sluggable: true`. Once again, that enables a listener that will be looking at our entities, whenever they're saved, to see if the `sluggable` behavior is activated. Before we activate the `sluggable` behavior, the first thing we need to do is create a property called `slug`.

At your terminal, run:

```terminal
./bin/console make:entity
```

Let's update `VinylMix` to add a new `slug` field. This will be a string, and let's limit it to a hundred characters. Having a string in our URL that's 255 characters long *doesn't* really make sense. We'll also make this *not* null, so it's required in the database. And that's it! Hit "enter" one more time to finish. That, not surprisingly, added a `slug` property here with `getSlug()` and `setSlug()` methods at the bottom.

One thing that the `make:entity` command *doesn't* ask you is whether or not you want a property to be *unique* in the database. In `slug`'s case, we *do* want it to be unique, so add `unique: true`. That will add a `unique` constraint in the database to make sure that we never get duplicates for this.

Okay, we have a new column, and we have a new field on the property. Now we need a migration to add that column to the database. Find your terminal and run:

```terminal
symfony console make:migration
```

As usual, I'll open up that new migration file to make sure it looks okay. And... perfect! It adds the `slug`, and then it also adds the `UNIQUE INDEX` on `slug`. And when we run it with

```terminal
symfony console doctrine:migrations:migrate
```

it *explodes*... and for the *same* reason as last time: `Not null violation`. We're adding a new `slug` column to our table that is *not null*, which means that any existing records won't really work. So, as I said before, if your database was already in production, you would need to fix this. But since ours *isn't*, we're just going to reset the database like we did last time:

```terminal
symfony console doctrine:database:drop --force
```

Then we'll recreate the database

```terminal
symfony console doctrine:database:create
```

and finally re-run all of the migrations from the very beginning:

```terminal
symfony console doctrine:migrations:migrate
```

And... `4 migrations executed`. Perfect!

At this point, we've activated the `sluggable` listener and added a `slug` column. But we're *still* missing a step. I'll prove it by going to `/mix/new` and... *error*: `[...] column "slug" of relation "vinyl_mix" violates not-null constraint`. So nothing is setting the `slug` property yet. To tell the `stof_doctrine_extensions` library that this is a `slug` property that should be set automatically, we need to add - *surprise* - an attribute. It's called `#[Slug]`. Hit "tab" to autocomplete that, which will add the `use` statement that you need on top. Then, we need to say `fields`, which is set to an array, and inside, just say `title`. This basically says `use the "title" field to generate this slug`. And now... looks like it's working! If we check the database...

```terminal
symfony console doctrine:query:sql 'SELECT * FROM vinyl_mix'
```

Awesome! The `slug` is down here and you can see the library is also smart enough to add a little `-1`, `-2`, `-3`, and so on to keep it unique.

Now that we have this `slug` column, over in `MixController.php`, we can change our `show` action to use `slug` here instead. And that's actually all we need to do here. Because we're calling this `slug`, it's now going to use the `slug` property on`VinylMix` to do the query.

The *other* important thing we need to do is make sure that we update our code any time we generate a URL to this route. For example, if I copy `app_mix_show` and search inside this file, we use it down here to redirect after we vote. So now, instead of passing the ID wildcard, we need to pass `slug` set to `$mix->getSlug()`. And if you search, there's one other part in our code that we need to update. It's `/templates/vinyl/browse.html.twig`. Right here, we need to change the link on the "Browse" page to `slug: mix.slug`.

Okay, let's try it. Let me refresh a few times here. Then head back to the homepage... click "Browse Mixes", and... awesome! There's a list! If we click one of these items... beautiful! It used this log and it *queried* via this log. Life is good.

Right now, to get dummy data to play with, we've created this new action. But that's a pretty poor way to add dummy data. It's pretty manual and requires us to refresh the page a bunch of times. The data is also a little random and un-interesting.

Next, let's add a proper data fixture system to remedy this.

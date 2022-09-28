# Clean URLs with Sluggable

Using a database ID in your URL is... kind of lame. It's more common to
use *slugs*. A slug is a URL-safe version of the name or title of an item.
In this case, the title of our mix.

To make this possible, we only need to do one thing: give our `VinylMix` class
a `slug` property that *holds* this URL-safe string. Then, it'll be super easy to
query for it. The only trick is that... something needs to *look* at the mix's title
and *set* that `slug` property whenever a mix is saved. And, ideally that could
happen automatically... cause I'm feeling kinda lazy... and I don't really want
to do that work manually *everywhere*. Whelp, *that* is the job of the sluggable
behavior from Doctrine Extensions.

## Activating the Sluggable Listener

Head back to `config/packages/stof_doctrine_extensions.yaml` and add
`sluggable: true`. 

[[[ code('35894f3389') ]]]

Once again, this enables a listener that will be *looking* at
each entity, whenever one is saved, to see if the sluggable behavior is activated
on it. How do we do that?

## Adding the Slug Property

First, we need a `slug` property on our entity. To add it, at your terminal, run:

```terminal
php bin/console make:entity
```

Update `VinylMix` to add a new `slug` field. This will be a string, and let's
limit it to a 100 characters. Also make this *not* nullable: it should be required
in the database. And that's it! Hit "enter" one more time to finish.

That, not surprisingly, added a `slug` property.. plus `getSlug()` and `setSlug()`
methods at the bottom.

[[[ code('48c554d427') ]]]

One thing the `make:entity` command *doesn't* ask you is whether or not you want
a property to be *unique* in the database. In `slug`'s case, we *do* want it to be
unique, so add `unique: true`. That will add a `unique` constraint in the database
to make sure that we never get duplicates.

[[[ code('87b124d933') ]]]

Before we think about any sluggable magic, generate a migration for the new property:

```terminal
symfony console make:migration
```

As usual, I'll open up that new file to make sure it looks okay. And... it does!
It adds `slug` including a `UNIQUE INDEX` for `slug`. And when we run it with

```terminal
symfony console doctrine:migrations:migrate
```

it *explodes*... for the *same* reason as last time: `Not null violation`. We're
adding a new `slug` column to our table that is *not null*... which means that any
existing records won't work. As I said in the previous chapter, if your database
is already on production, you would need to fix this. But since ours is *not*, we
can cheat and reset the database like we did before:

```terminal
symfony console doctrine:database:drop --force
```

Then:

```terminal
symfony console doctrine:database:create
```

Finally re-run all of the migrations from the very beginning:

```terminal
symfony console doctrine:migrations:migrate
```

And... yes! 4 migrations executed.

## Adding the Sluggable Attribute

At this point, we've activated the sluggable listener and added a `slug` column.
But we're *still* missing a step. I'll prove it by going to `/mix/new` and...
*error*:

> [...] column "slug" of relation "vinyl_mix" violates not-null constraint.

Yup! Nothing is *setting* the `slug` property yet. To tell the extensions library
that this is a `slug` property that it should set automatically, we need to add -
*surprise* - an attribute! It's called `#[Slug]`. Hit "tab" to autocomplete that,
which will add the `use` statement that you need on top. Then, say `fields`, which
is set to an array, and inside, just `title`.

[[[ code('1fd8df0329') ]]]

This says:

> use the "title" field to generate this slug.

And now... it looks like it's working! If we check the database...

```terminal
symfony console doctrine:query:sql 'SELECT * FROM vinyl_mix'
```

Woohoo! The `slug` is down here... and you can see the library is *also* smart enough
to add a little `-1`, `-2`, `-3` to keep it unique.

## Updating our Route to use {slug}

Now that we have this `slug` column, over in `MixController`, let's make our
route trendier by using `{slug}`.

[[[ code('28ca5110ee') ]]]

What else do we need to change here? Nothing! Because the route wildcard is
now called `{slug}`, Doctrine will use this value to query from the `slug` property.
Genius!

## Updating Links to the Route

Though, we do need to update any links that we generate *to* this route. Watch:
copy the route name - `app_mix_show` - and search inside this file. Yup! We use
it down here to redirect after we vote. Now, instead of passing the `id` wildcard,
pass `slug` set to `$mix->getSlug()`.

[[[ code('234578269e') ]]]

And if you searched, there's one other place we generate a URL to this route:
`templates/vinyl/browse.html.twig`. Right here, we need to change the link on the
"Browse" page to `slug: mix.slug`.

[[[ code('34ca4a0c54') ]]]

Testing time! Let me refresh a few times... then head back to the homepage...
click "Browse Mixes", and... there's our list! If we click one of these mixes...
beautiful! It used the slug and it *queried* via the slug. Life is good.

Ok, right now, to add dummy data so we can use the site, we've created this `new`
action. But that's a pretty poor way to handle dummy data: it's manual, requires
refreshing the page and, though we have *some* randomness, it creates boring data!

So next, let's add a proper data fixture system to remedy this.

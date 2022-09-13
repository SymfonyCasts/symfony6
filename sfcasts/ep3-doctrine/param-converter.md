# Param Converter & 404's

We've programmed the happy path. When I go to `/mix/13`, my database *does* find a
mix with that id and... life is *good*. But what if I change this to `/99`? *Yikes*.
That's a 500 error: *not* something we want our site to *ever* do. This really *should*
be a 404 error. So, how do we trigger a 404?

## Triggering a 404 Page

Over in the method, this `$mix` variable will either be a `VinylMix` object *or* null
if one isn't found. So we can say `if (!$mix)`, and then, to trigger a 404,
`throw $this->createNotFoundException()`. You can give this a message if you want,
but it'll only be seen by developers.

[[[ code('743daf7827') ]]]

This `createNotFoundException()`, as the name suggests, creates an exception
object. So we're actually *throwing* an exception here... which is nice, because
it means that code *after* this won't be executed.

Now, *normally* if you or something in your code throws an exception, it will trigger
a 500 error. But this method creates a *special* type of exception that maps to a 404.
Watch! Over here, in the upper right, when I refresh... 404!

By the way, this is *not* what the 404 or 500 pages would look like on production.
If we switched to the `prod` environment, we'd see a pretty generic error page
with no details. Then you *customize* how those look, even making separate styles
for 404 errors, 403 Access Denied errors, or even... *gasp* ... 500 errors if
something goes *really* wrong. Check out the Symfony docs for how to customize
error pages.

## Param Converter: Automatic Query

Okay! We've queried for a single `VinylMix` object and even handled the 404 path.
But we can do this with *way* less work. Check it out! Replace the `$id` argument
with a *new* argument, type-hinted with our entity class `VinylMix`. Call it,
how about, `$mix` to match the variable below. Then... delete the query...
and also the 404. And now, we don't even need the `$mixRepository` argument at all.

[[[ code('11e978077a') ]]]

This... deserves some explanation. So far, the "things" that we are "allowed" to
have as arguments to our controllers are (1) route wildcards like `$id` or (2) services.
Now we have a *third* thing. When you type-hint an *entity* class, Symfony will
query for the object *automatically*. Because we have have a wildcard called `{id}`,
it will take this value (so "99" or "16") and query for a `VinylMix` whose `id`
is *equal* to that. The name of the wildcard - `id` in this case - needs to match
the property name it should use for the query.

But if I go back and refresh... it *doesn't* work!?

> Cannot autowire argument `$mix` of `MixController::show()`: it references
> `VinylMix` but no such service exists.

We *know* this isn't a service... so that make sense. But... why isn't it querying
for the object like I just said it would?

Because... to get this feature to work, we need to install another bundle! Well,
if you're using Symfony 6.2 and a new enough DoctrineBundle - probably version 2.8 -
then this *should* work without needing *anything* else. But since we're using Symfony
6.1, we need one extra library.

Find your terminal and say:

```terminal
composer require sensio/framework-extra-bundle
```

This is a bundle full of nice little shortcuts that, by Symfony 6.2, will all have
been moved into Symfony itself. So eventually, you won't need this.

And now... without doing anything else... it works! It automatically queried for
the `VinylMix` object and the page renders! And if you go to a bad ID, like
`/99`... yes! Check it out! We get a 404! This feature is called a "ParamConverter"...
which is mentioned in the error:

> `VinylMix` object not found by the `@ParamConverter` annotation.

*Anyways*, I *love* this feature. If I need to query for *multiple* objects,
like in the `browse()` action, I'll use the correct repository service. But if I
need to query for a *single* object in a controller, I use this trick.

Next, let's make it possible to up vote and down vote our mixes by leveraging a simple
form. To do this, for the first time, we will *update* an entity in the database.

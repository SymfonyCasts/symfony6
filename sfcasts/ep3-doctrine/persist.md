# Persisting to the Database

Now that we have an entity class and corresponding table, we're ready to
save some stuff! So... how *do* we insert rows into the table? Wrong question!
We're *only* going to focus on *creating objects* and *saving* them. Doctrine will
handle the insert queries *for* us.

To help do this in the simplest way possible, let's make a fake "new Vinyl Mix"
page.

In the `src/Controller/` directory, create a new `MixController` class and make
this extend the normal `AbstractController`. Perfect! Inside, add a
`public function` called `new()` that will return a `Response` from HttpFoundation.
To make this a page, above, use the `#[Route]` attribute, hit "tab" to
autocomplete that and let's call the URL `/mix/new`. Finally, to see if this
is working, `dd('new mix')`.

[[[ code('550f9491ea') ]]]

In the real world, this page might render a form. Then, when we submit that form,
we would take its data, create a `VinylMix()` object and save it. We'll work on
stuff like that in a future tutorial. For now, let's just see if this page works.
Head over to `/mix/new` and... got it!

Ok, let's go create a `VinylMix()` object! Do that with `$mix = new VinylMix()`...
and then we can start setting data on it! Let's create a mix of one of my absolute
*favorite* artists as a kid. I'll quickly set some other properties... we need to
set, at the very least, all of the properties that have *required* columns in the
database. For `trackCount`, how about some randomness for fun. And, for `votes`,
the same thing... including *negative* votes... though the Internet would *never*
be so cruel as to downvote any of my mixes *that* much. Finally, `dd($mix)`.

[[[ code('1fe585eee9') ]]]

So far, this has *nothing* to do with Doctrine. We're just creating an object and
setting data onto it. This data is hard-coded, but you can imagine replacing this
with whatever the user just submitted via a form. Regardless of where we get
the data, when we refresh... we have an object with data on it. Cool!

## Services vs Entities

By the way, our entity class, `VinylMix`, is the *first* class we've created that
is *not* a service. There are generally *two* types of classes. First, there are
*service* objects, like `TalkToMeCommand` or the `MixRepository` we created in
the last tutorial. These objects do *work*... but they don't hold any data besides
*maybe* some basic config. And we always fetch services from the container, usually
via autowiring. *We* never instantiate them directly.

The *second* type of classes are *data* classes like `VinylMix`. The primary job of
these classes is to hold *data*. They don't usually *do* any work except maybe
some basic data manipulation. And unlike services, we *don't* fetch these objects
from the container. Instead, we create them manually wherever and whenever we need
them, like we just did!

## Hello Entity Manager!

Anyway, now that we have an object, how can we *save* it? Well, saving something to
the database is *work*. And so, no surprise, that work is done by a *service*! Add
an argument to the method, type-hinted with `EntityManagerInterface`. Let's call
it `$entityManager`.

`EntityManagerInterface` is, by far, *the* most important service for Doctrine. We're
going to use it to *save*, *and* indirectly when we *query*. To save, call
`$entityManager->persist()` and pass it the object that we want to save (in this
case, `$mix`). Then we also need to call `$entityManager->flush()` with *no* arguments.

[[[ code('7fcf0dd0be') ]]]

But... wait. Why do we have to call *two* methods?

Here's the deal. When we call `persist()`, that doesn't actually *save* the object
or talk to the database at *all*. It just tells Doctrine:

> Hey! I want you to be "aware" of this object, so that later when we call `flush()`,
> you'll know to save it.

Most of the time, you'll see these two lines together - `persist()` and then
`flush()`. The reason it's split into two methods is to help with batch data loading...
where you could persist *a hundred* `$mix` objects and then *flush*
them to the database *all at once*, which is more efficient. But most of the time,
you'll call `persist()` and *then* `flush()`.

Okay, to make this a valid page, let's `return new Response()` from
HttpFoundation and I'll use `sprintf` to return a message:
`mix %d is %d tracks of pure 80\'s heaven`... and for those two wildcards,
pass `$mix->getId()` and `$mix->getTrackCount()`.

[[[ code('120c0e0334') ]]]

Let's try it! Move over, refresh and... yes! We see "Mix 1". That's so cool! *We*
never actually *set* the ID (which makes sense). But when we *saved*, Doctrine
grabbed the new ID and put that onto the `id` property.

If we refresh a few more times, we get mixes 2, 3, 4, 5, and 6. That's super fun.
All *we* had to do is persist and flush the object. Doctrine handles all of the
querying stuff *for* us.

Another way we can prove this is working is by running:

```terminal
symfony console doctrine:query:sql 'SELECT * FROM vinyl_mix'
```

This time, we *do* see the results. *Awesome*!

Okay, now that we have stuff in the database, how do we *query* for it? Let's tackle
that *next*.

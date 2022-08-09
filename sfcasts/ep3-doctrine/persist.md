# Persisting to the Database

Now that we have a fully functional class and the corresponding table, we're ready to save some stuff! So... how do we insert rows into the table? The answer: Wrong question! We're going to focus on *creating objects* and *saving* them instead. Doctrine will handle the insert queries *for* us. to help do this in the simplest way possible, let's create a new *fake* Vinyl Mix page.

In the `/src/Controller` directory, let's create a new `MixController.php` and make this extend the normal `AbstractController`. Perfect! Inside, we'll add a `public function` called `new()` that will return a `Response` from HttpFoundation. Above this, to make this a page, we'll use the `#[Route]` annotation. Hit "tab" to autocomplete that, and we'll call the URL `/mix/new`. Finally, just to see if this is working, I'll `dd('new mix')`.

In the real world, this page might render a form. And then, when we submit the form, it will create a `VinylMix()` object and save it. We'll work on stuff like that in a future tutorial, but for now, let's just see if this page works. Head over to `/mix/new` and... got it!

Now that we have a working page, let's create a new `VinylMix()` object. Say `$mix = new VinylMix()`, and then we can start setting some data on this. I'm creating a mix of one of my absolute *favorite* artists as a kid. I'll quickly add some of the other fields, making sure to set, at the very least, all of the properties that have *required* columns in the database. For track count, I'll add little randomness just for fun. And then, for votes, I'll do the same thing, and even allow some negative votes where people can downvote the mix. *Rude*... And finally, at the bottom, let's `dd($mix)`.

So far, this has nothing to do with doctrine. This is just creating an object and setting data on it. This data is hard-coded, but you can imagine, if we submitted a form, we could read data from the form and use that here. Regardless of how we do it, when we refresh... we have an object with data on it. Cool!

By the way, our empty class, `VinylMix` is the first class we've created that is *not* a service. Remember, there are generally *two* types of objects. First, there are *service* objects, like `talk-to-me` command or the `MixRepository` we created in the last tutorial. These objects do *work*, but they don't hold any data besides *maybe* some basic config. And we usually fetch services from the container via autowiring.

The *second* type of classes are data classes like `VinylMix`. The primary job of these classes is to *hold* data. They don't usually *do* any work except for maybe some basic data manipulation inside of one of its methods. And unlike services, we *don't* fetch these objects from the container. Instead, we just create them manually wherever and whenever we need them, like we just did.

Anyway, now that we have an object, how can we *save* it? Well, saving something in the database is *work*. And, no surprise here, that work is done by a *service*. So let's add an argument to the method, typehinted with `EntityManagerInterface`, and let's call it `$entityManager`. The `EntityManagerInterface` is, by far, the most important service for Doctrine. We're going to use it when we're *saving* things, and indirectly when we *query* things. To save, we'll call `$entityManager->persist()`,and pass it the object that we want to save (in this case, `$mix`). Then, let's also call `$entityManager->flush()` with no arguments.

At first, this may look a little weird. Why do we have to call *two* methods? When we call `persist()`, that doesn't actually *save* the object. It just tells Doctrine:

`Hey! I want you to be aware of this object, so
that later when we call flush(), you'll save it`

Most of the time, you'll see these two lines together - `persist()` and then `flush()`. The reason it's split into two pieces is that, if you were doing some batch data loading, you could persist *a hundred* `$mix` objects and then *flush* them to the database *all at once*, which is more efficient. But most of the time, you'll call `persist()` and *then* `flush()`.

Okay, now, to make this a valid page, let's `return new Response()` from HttpFoundation and I'll use the `sprintf` function so we return a little message. Here, I'll say `mix %d is %d tracks of pure 80\'s heaven`. And for those two `%d`s, I'm going to pass `$mix->getId()` and `$mix->getTrackCount()`.

Let's try it! Move over, refresh and... yes! We see "Mix 1". Check it out! We never actually *set* the ID up here (which makes sense), but when we *saved* it to the database, Doctrine grabbed the ID and made sure that the ID property was set. So if we refresh this a few more times, we get mixes 2, 3, 4, 5, and 6. That's super cool! All we had to do is save the object. Doctrine's handling all of the querying stuff *for* us.

Another way we can prove this is working is by running:

```terminal
symfony console doctrine:query:sql 'SELECT * FROM vinyl_mix'
```

This time, we *do* see the results. *Awesome*!

Okay, now that we have stuff in the database, how do we *query* for it? Let's tackle that *next*.

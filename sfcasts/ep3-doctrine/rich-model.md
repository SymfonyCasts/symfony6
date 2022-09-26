# Flash Message & Rich vs Anemic Models

After we submit a form successfully, we *always* redirect. Often times, we'll *also*
want to show the user a success message so they *know* everything worked. Symfony
has a special way to handle this: *flash messages*.

To *set* a flash message, before redirecting, call `$this->addFlash()` and pass,
in this situation, `success`. For the second argument, put the message that you
want to show to the user, like `Vote counted!`.

[[[ code('9733851b63') ]]]

The `success` key could be anything... it's kind of like a "category" for the flash
message... and you'll see how we use that in a minute.

Flash messages have a fancy name, but they're a simple idea; Symfony stores flash
messages in the user's *session*. What makes them special is that Symfony will
*remove* the message *automatically* as soon as we *read* it. They're like
self-destructing messages. Pretty cool.

## Reading Flash Messages

So... how *do* we read them? The way I like to do it is by opening up my base template -
`base.html.twig` - and reading and rendering them here. Let's put it right after
the navigation but before the `{% block body %}`. Say `{% for message in %}`. Then,
we want to read out any `success` category flash messages we might have. To do this,
we can leverage the *one* global Twig variable in Symfony: `app`. This has several
methods on it, like `environment`, `request`, `session`, the current `user`, or
one called `app.flashes`. Pass *this* the *category* (in our case,`success`). As
I mentioned, this could be *anything*. If you put `dinosaur` as the key in a controller,
then you'd read the `dinosaur` messages out *here*. Finish with `{% endfor %}`.

[[[ code('ca33c9b940') ]]]

Typically, you'll only have *one* success message in your flash at a time, but
*technically* you can have multiple. That's why we're looping over them.

Inside of this, render a `<div>` with `class="alert alert-success"` so it looks
like a *happy* message. Then, print out `message`.

[[[ code('23490be0be') ]]]

So if this works correctly, it will read all of our `success` flash messages and
render them. And once they've been read, Symfony will *remove* them so that they
won't render again on the *next* page load. By putting this in the base template,
we can now set flash messages from *anywhere* in our app and they'll be rendered
on the page. Pretty cool.

Watch. Head back to our page, upvote and... beautiful! We'll probably want to
remove this extra margin in a real project, but we'll leave it for now.

## Making our Entity Class Smarter

All right, look back at `MixController`. The logic for doing our "up" and "down"
voting is pretty simple... but I think it can be better. Try this! Open up
`VinylMix`... and scroll down to `setVotes()`. Right after this, just to keep
things organized, create a new `public function` called `upVote()` and return `void`.
Inside, say `$this->votes++`. Copy that, and create a *second* method which we'll
call - you guessed it - `downVote()`... with `$this->votes--`.

[[[ code('f6a52d95fb') ]]]

Thanks to these methods, in `MixController`, instead of having `$mix->setVotes()`
set to `$mix->getVotes() + 1`, we can just say `$mix->upVote()`... and `$mix->downVote()`.

[[[ code('49312b26a8') ]]]

Now *that's* nice. Our controller reads much more clearly, and we've encapsulated
the `upVote()` and `downVote()` logic *into* our entity. If we head over and refresh,
it *still* works.

## Smart vs Anemic Models

This highlights an interesting topic. We've now added *four* custom methods
to our entity: two that help read the data in a special way, and two that help *set*
data. When we run `make:entity`, it creates getter and setter methods for every
single property. That's nice, because it makes our entity *infinitely* flexible.
*Anyone* from *anywhere* can read or set any property. But sometimes, you might *not*
want or need that. For example, do we really want a `setVotes()` method? Is there
really a use case in our code for something to set the vote count to *any* number
it wants? Probably not. We'll likely only need `upVote()` and `downVote()`. I *will*
keep the `setVotes()` method... though, because we use it when we generate
our dummy `VinylMix` object.

But, in general, by removing unnecessary getter and setter methods in your entity
and replacing them with more descriptive methods like `upVote()`, `downVote()`,
`getVoteString()`, or `getImageUrl()` - methods that fit your business logic - you
can, little by little, give your entities more clarity. Our `upVote()` and
`downVote()` methods are *super* clear and descriptive. Someone calling these doesn't
even need to know or *care* how they work internally.

Entities that *only* have getter and setter methods are sometimes called "anemic
models". Entities that *remove* these and replace them with specific methods for your
business logic are sometimes called "rich models". Some people take this to an
extreme and have almost *no* getter or setter methods. Here at SymfonyCasts, we tend
to be pragmatic. We usually *do* have getter and setter methods, but we always
look for ways to be more descriptive, like by adding `upVote()` and `downVote()`.

Next, let's install an *awesome* library called DoctrineExtensions. This is a magic
library full of superpowers, like automatic timestampable, and slug creation
behaviors.

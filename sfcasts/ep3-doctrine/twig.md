# Custom Entity Methods & Twig Magic

Our `VinylMix` entity has a `$votes` integer property... but we're not printing that
on the `/browse` page just yet. *No problem*. Over in
`templates/vinyl/browse.html.twig`, after `createdAt`, add a line break
and print `mix.votes`... (which even autocompleted for us)! If we float over and
refresh... nice! We can see the votes, which can be positive *or* negative because,
alas, the Internet *can* apparent be an unfriendly place!

## The Built-in Repository Methods

Right now, we're querying the database and the results are coming back in whatever
order the database wants. How could we order these by the highest votes first? Well,
we *could* write a custom query inside of `VinylMixRepository`, which we'll learn
about soon. But these repository classes have several methods that allow us to,
at least, make some basic queries.

For example, we can, of course, call `findAll()`... or we could call `find()` and
pass it the ID to find a *single* `VinylMix`. And there are a few others, like
`findOneBy()` or `findBy()`, where you pass it an array of criteria. To control
the order, we can leverage `findBy()`. This will return an array of results for
whatever criteria we pass. For example, we could, put `name` set to some particular
name. But instead, leave that *empty* so it returns *everything*. Why? Because
I want to take advantage of the second argument: the "order". Pass this an array
with `'votes' => 'DESC'`.

And now... nice! The highest votes are first!

## Adding a Custom Entity Method

As I mentioned, the votes can be positive or negative. To make it even *more* obvious
when they're positive, I want to print a plus sign in front of the positive
votes. We *could* do that by adding some logic into our template directly. But
remember, we have this nice entity class! At the moment, it only has getter and setter
methods in it, but we *are* allowed to add our own custom methods. And that's
a *great* way to organize your code.

Check it out: create a new public function called, how about `getVotesString()`,
which will return a `string`. We can calculate the "+" or "-" prefix with some
fancy logic:

> If the votes are equal to zero, then we want *no prefix*. If the votes are greater
> than or equal to zero, we want a plus symbol. Else we want a minus symbol.

And... let me surround this entire second statement in parenthesis. If that's a
little confusing, you do *not* need to do that all in *one* line with two "if"
statements on top of each other. There's no extra credit for writing fancy,
confusing code. Feel free to break this into multiple lines to get the job done.

Now, at the bottom, `return sprintf()` and with `%s`, which will be the prefix, and
`%d`, which will be the vote count. Pass these in: `$prefix` then the absolute value
of `$this->votes`... since we're putting the negative sign in manually.

Now that we have this nice method, we can use anywhere in our project to get the
`votes` string. For example, in the template, we can call this by saying
`mix.getVotesString()`. *But* ,we don't really need to do that. Instead say
`mix.votesString`.

Twig is smart enough to realize that `votesString` is *not* a real property...
but it will see that there *is* a `getVotesString()` method. And so, it will call
*that*. Think of this as a virtual property inside of Twig.

If we fly back over and refresh... awesome! We get the minus *and* plus signs.

## A Second Custom Entity Method!

While we're talking about this, the broken images - caused by the placeholder site
I'm using being down - are... kind of annoying! So let's refactor those too.

In a real app, we'll probably let our users upload these... though for now, we'll
stick with dummy images. But either way, we'll probably need to be able to get the
URL to a vinyl mix's image from multiple places in our code. To make that easy
*and* keep the code centralized, let's add another method to our entity!

How about `public function getImageUrl()`. Give this a `$width` argument so we
can ask for different sizes. Inside I'm going paste in some code that uses a
*different* service for dummy images. This looks a bit fancy - but I'm just trying
to use the id to get a predictable, but random image... skipping the first 50,
which are all nearly identical on this site.

Anyways, wow we have this nice reusable method!

Back in the template... up here is where I have the hardcoded image URL. Replace
this with `mix.imageUrl()`, but this time, we *do* need to pass an argument. Pass
`300`... and let's update the `alt` attribute as well with `Mix album cover`.

If we go over and refresh... *lovely*. Our mixes have images!

## Cleanup: Deleting the Old Repository

There's one last *tiny* cleanup thing I want to do before we keep going. We no longer
need this `MixRepository` service, which loads mixes from GitHub. Let's delete
this so I don't get confused... since its name is *so* similar to our new
`VinylMixRepository`. I'll right click on `MixRepository.php`, go to "Refactor",
and click on "Safe Delete".

Easy! But... we *might* still be using that somewhere, right? If you go to your
terminal and run:

```terminal
git grep MixRepository
```

that'll show you where in your code that's being used.

Though, Symfony's service container is so smart, it will often *tell* us if we've
messed something up, *like* if we're still using a service that doesn't exist. For
example, try refreshing any page. Yup!

> Cannot autowire service `App\Command\TalkToMeCommand`: argument
> `$mixRepository` of method `__construct()` has type `App\Service\MixRepository`
> but this class is missing a parent class.

Even though this page doesn't even *use* this `TalkToMeCommand` class, it figured
out that there's a problem with it. Open it up: `src/Command/TalkToMeCommand.php`.
Yep! We were using `MixRepository`... so that we could call its `findAll()` method.
Change that to use `VinylMixRepository`... and then we can remove the `use` statement
on top. The `VinylMixRepository` *still* has a `findAll()` method, so this will
*still* work. This isn't a very efficient way to find a random mix, but it's good
enough for now.

Ok,close that class and go refresh again. The service container found *another*
problem spot in `VinylController`! Head over there and... up in the constructor...
yep! We're autowiring it here too. But... we're not even using that property anymore,
so we can remove it. Also delete its `use` statement and a couple of other `use`
statements that are not being... uh... used anymore more.

And now... the site works again!

Next, let's learn how to build custom queries via custom repository methods.

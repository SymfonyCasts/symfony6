# Custom Entity Methods & Twig Magic

Our `VinylMix` entity has a `$votes` integer property... but we're not *printing*
that on the page... just yet. Let's do that. Over in
`templates/vinyl/browse.html.twig`, after `createdAt`, add a line break
and print `mix.votes`... (which even autocompleted for us)! If we float over and
refresh... nice! We see the votes, which can be positive *or* negative because,
alas, the Internet *can* apparently be an unfriendly place!

## The Built-in Repository Methods

Right now, we're querying the database and the results are coming back in whatever
order the database wants. Could we order these by the highest votes first? Sure!
One option is to write a custom query inside of `VinylMixRepository`, which we'll
learn about soon. But these repository classes have several methods that allow us
to, at least, do some basic stuff!

For example, we can call `findAll()`... or we could call `find()` and
pass it an ID to find a *single* `VinylMix`. And there are others, like
`findOneBy()` or `findBy()`, where you pass it an array of criteria to use in
a WHERE clause. For example, we could find all mixes WHERE name equals some value.

But for this situation, leave that criteria *empty* so it returns *everything*.
Why? Because I want to leverage the second argument: the "order". Pass an
array with `'votes' => 'DESC'`.

[[[ code('e346ea1578') ]]]

And now... nice! The highest votes are first!

## Adding a Custom Entity Method

Ok, so votes can be positive or negative. To make that *super* obvious, I want
to print a plus sign in front of the positive votes. We *could* do that by adding
some logic in Twig. But remember, we have this nice entity class! Sure, right
*now* it only has getter and setter methods. But we *are* allowed to add our own
custom methods. And that's a *great* way to organize your code.

Check it out: create a new public function called, how about `getVotesString()`,
which will return a 🥝. I'm kidding, it'll return a `string` of course. Then calculate
the "+" or "-" prefix with some fancy logic that says:

> If the votes are equal to zero, we want *no prefix*. If the votes are greater
> than zero, we want a plus symbol. Else we want a minus symbol.

And... let me surround this entire second statement in parenthesis. This is probably
the fanciest line of code I've ever written... which also means it's the most
confusing! Feel free to break this onto multiple lines.

[[[ code('dc5b1303a1') ]]]

At the bottom, `return sprintf()` with `%s`, which will be the prefix, and
`%d`, which will be the vote count. Pass these in: `$prefix` then the absolute value
of `$this->votes`... since we're adding the negative sign in manually.

[[[ code('c25b180e57') ]]]

We can now use this nice method anywhere in our app... like from inside a template
with `mix.getVotesString()`. *Or* shorten this to `mix.votesString`.

[[[ code('bb847889bf') ]]]

Twig is smart enough to realize that `votesString` is *not* a real property...
but that there *is* a `getVotesString()` method. And so, it will call *that*.
Think of this as a virtual property inside of Twig.

If we fly back over and refresh... awesome! We get the minus *and* plus signs.

## A Second Custom Entity Method!

While we're here, the broken images - caused by the placeholder site
I'm using being down - are... kind of annoying! Time to fix those!

In a real app, we'll probably let our users upload real images... though for now,
we'll stick with dummy images. But either way, we'll probably need the ability to
get the URL to a vinyl mix's image from multiple places in our code. To make that
easy *and* keep the code centralized, let's add another entity method!

How about `public function getImageUrl()`. Give this a `$width` argument so we
can ask for different sizes. Inside I'll paste in some code that uses a *different*
service for dummy images. This looks a bit fancy - but I'm just trying to use the
id to get a predictable, but random image... skipping the first 50, which are all
nearly identical on this site.

[[[ code('e77ea4b80b') ]]]

Anyways, now we have this nice reusable method!

Back in the template... up here is where I have the hardcoded image URL. Replace
this with `mix.imageUrl()`, but this time, we *do* need to pass an argument. Pass
`300`... and let's update the `alt` attribute as well to `Mix album cover`.

[[[ code('53ce836c2f') ]]]

If we go over and refresh... *lovely*. Our mixes have images!

## Cleanup: Deleting the Old Repository

Ok one last *tiny* cleanup thing. We no longer need this `MixRepository` service,
which loads mixes from GitHub. Let's delete it so I don't get confused... since
its name is *so* similar to the new `VinylMixRepository`. Right click on `MixRepository.php`, go to "Refactor", and click on "Safe Delete".

Easy! But... we *might* still be using that somewhere, right? If you go to your
terminal and run:

```terminal
git grep MixRepository
```

that'll show you where it's still being mentioned.

Though, Symfony's service container is so smart, it will often *tell* us if we've
messed something up, like if we're still using a service that doesn't exist. Watch.
Try refreshing any page. Yup!

> Cannot autowire service `App\Command\TalkToMeCommand`: argument
> `$mixRepository` of method `__construct()` has type `App\Service\MixRepository`.

Even though this page doesn't even *use* the `TalkToMeCommand` class, it figured
out that there's a problem with it. Open it up: `src/Command/TalkToMeCommand.php`.
Yep! We were using `MixRepository`... so that we could call its `findAll()` method.
Change that to use `VinylMixRepository`... and then we can remove the `use` statement
on top. The `VinylMixRepository` *still* has a `findAll()` method, so this will
*still* work. This isn't a very efficient way to find a random mix, but it's good
enough for now.

[[[ code('5c17368962') ]]]

Ok, close that class and go refresh again. The service container found *another*
problem spot in `VinylController`! Head over there and... up in the constructor...
yep! We're autowiring it here too. But... we're not even using the property anymore,
so remove it. Also delete its `use` statement and a couple of other `use`
statements that are not being... uh... used anymore more.

[[[ code('78a32d8a81') ]]]

And now... the site works again!

Next, let's learn how to build custom queries via the query builder!

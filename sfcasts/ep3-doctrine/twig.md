# Twig

Our `VinylMix` entity has a `$votes` integer property, but we're not printing that out on the `/browse` page just yet. *No problem*. Over in `/templates/vinyl/browse.html.twig`, after the `createdAt`, add a line break with `<br>`, print out `mix.votes` (which even autocompletes for us), and then `votes`. If we float over and refresh... nice! We can see the votes, which can be positive *or* negative because, eventually, we'll allow our mixes to be upvoted or downvoted.

Right now, we're querying the database and the results are coming back in whatever order the database wants. How could we query to order these by the highest votes first? Well, we *could* write a custom query inside of `VinylMixRepository`, which we'll learn about soon. But these repository classes have several methods to at least let us do some basic things.

For example, we can, of course, call `findAll()`, but we can also call `find()` and pass it the ID to find a single vinyl mix. And there are a few others, like `findOneBy()` or `findBy()`, where you can pass it an array of criteria. In this case, what we can do is actually leverage `findBy()`. This will return an array of results that match whatever criteria we pass here. We could, in theory, put `name` set to some particular name. I'm going to leave that *empty* so it returns *everything*. Then, let's take advantage of this second argument, which is the `orderBy` and say `'votes' => 'DESC'`. And now... nice! The highest votes are first!

Right now, these votes can be positive or negative. To make it even more obvious when they're positive, I want to print a little plus sign in front of the positive votes. We *could* do that by adding some logic into our template directly. But remember, we have this nice entity class. At the moment, it only has getter and setter methods in it, but we *are* allowed to add our own custom methods to help us fetch the data a little easier.

I'll show you. Let's create a new public function - `public function getVotesString()` - which will return a `string`. We can calculate the prefix like "positive" or "negative" with some fancy logic here that says:

`If the votes are equal to zero, then we want
*no prefix*. If the votes are greater than or
equal to zero, we want a plus symbol.
Else we want a minus symbol.`

And let me actually surround this entire second statement in parenthesis. If that's a little confusing to you, you do *not* need to do that all in one line with two "if" statements on top of each other. You can break that down if you want to, but that should get the job done.

Down here, we can `return sprintf()` and we'll return `%s`, which will be the prefix, and `%d`, which will be the number of votes. And then we'll fill that in with `$prefix`. Finally, I'll use the absolute value of `$this->votes` since we're putting the negative sign in manually.

So we now have this nice method we can reuse anywhere in our project to get the `votes` string. Inside our template, we can call this by saying `mix.getVotesString` if we want to. *But* we don't really need to do that. We can just say `mix.votesString`. Remember, Twig is smart enough to realize that `votesString` isn't a real property, but it will see that there's a `getVotesString` and it will call that. Think of it as a virtual property inside of Twig. If we float back over and refresh... awesome! We get the plus *and* the negative!

While we're talking about this, the broken images for my placeholder site that's currently down are kind of annoying, so let's refactor those too. It might be handy to be able to grab the URL to a vinyl mix's image from multiple places in our project. To do that, let's create a new `public function` here called `getImageUrl()`. And this time, let's add a `$width` argument, so you can control the size. Inside of this, I'm going to use a different API service which allows us to pass two different wildcards. The first is like an ID. They have about a thousand images there for us to pull from. For that, let's say `($this->getId() + 50%) % 1000`. Basically what we're doing is assigning a number between 0 and 1000 based on the ID. So ID #1 will always return the same image, ID #2 will always return the same image, and the `+ 50` is just there because the first 50 images are all pretty similar, so I'm skipping those. And down here, let's pass the `$width`. Cool! Now we have this nice reusable method.

Back in our template, up here is where I have the hard-coded image URL. Let's replace this with `mix.imageUrl()`, but this time, we *do* need to pass an argument. So I'll pass `300`, and let's update the `alt` tag as well with `Mix album cover`. If we go over and refresh... *lovely*. Our mixes have images!

There's one last *tiny* cleanup thing I want to do before we keep going. We no longer need this `MixRepository` service, which loads mixes from the database. I'm going to delete that so I don't get confused and try to use it, since its name is so similar to our new `VinylMixRepository` class. I'll hover over `MixRepository.php`, go to "Refactor", and click on "Safe Delete". But... we *might* still be using that somewhere, right? If you go to your terminal and run

```terminal
git grep MixRepository
```

that will show you where in your code that's being used. It's a good idea to do this, just to be safe. *But* Symfony's service container is so smart, it will often tell us if we've messed something up, *like* if we're still using a service that doesn't exist. For example, if you refresh any page, you'll get:

`Cannot autowire service
"App\Command\TalkToMeCommand": argument
"$mixRepository" of method "__construct()" has
type "App\Service\MixRepository" but this class
is missing a parent class`.

So even though this page doesn't even use this `TalkToMeCommand` class, it figured out that there's a problem with it. If you open `/src/Command/TalkToMeCommand.php`... yep! We were using `MixRepository` right here so that we could call the `findAll()` method on it. Let's change that to use `VinylMixRepository`, and then we can remove the `use` statement on top. The `VinylMixRepository` still has a `findAll()` method, so this will still work. This isn't a very efficient way to find a random mix, but it's good enough for now.

All right, I'll close that class and go refresh again. The service container found *one more* spot where we're using the old `MixRepository` in `VinylController.php`, so if we head over there, up in the constructor... yep! We're autowiring it there too. But we're not even using this property anymore, so we can remove it. I'll also remove its `use` statement and a couple of other `use` statements that are not being used anymore more. And now... got it!

Next, let's learn how to build custom queries to query however we want.

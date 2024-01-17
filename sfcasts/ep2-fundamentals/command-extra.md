# Command: Autowiring & Interactive Questions

Last chapter team! Let's do this!

Ok, what if we need a *service* from inside our command? For example, let's say
that we want to use `MixRepository` to print out a vinyl mix recommendation.
How can we do that?

Well, we're inside of a service and we need access to *another* service, which
means we need... the dreaded *dependency injection*. Kidding - not dreaded, easy
with autowiring!

Add `public function __construct()` with `private MixRepository $mixRepository`
to create and set that property all at once.

[[[ code('628bac0c7d') ]]]

Though, if you hover over `__construct()`, it says:

> Missing parent constructor call.

To fix this, call `parent::__construct()`:

[[[ code('f2801b5b65') ]]]

This is a *super* rare situation where the base class has a constructor that we
need to call. In fact, this is the *only* situation I can think of in Symfony
like this... so not *normally* something you need to worry about.

## Interactive Questions

Down here, let's output a mix recommendation... but make it even *cooler* by
first asking the user *if* they want this recommendation.

We can ask interactive questions by leveraging the `$io` object. I'll say
`if ($io->confirm('Do you want a mix recommendation?'))`:

[[[ code('cdef6583b7') ]]]

This will ask that question, and if the user answers "yes", return true. 
The `$io` object is *full* of cool stuff like this, including asking multiple 
choice questions, and auto-completing answers. Heck, we can even build a progress bar!

Inside the if, get all of the mixes with
`$mixes = $this->mixRepository->findAll()`. Then... we need just a bit of ugly
code - `$mix = $mixes[array_rand($mixes)]` - to get a random mix.

Print the mix with one more `$io` method `$io->note()` passing
`I recommend the mix` and then pop in `$mix['title']`:

[[[ code('bb2cf7650b') ]]]

And... done! By the way, notice this `return Command::SUCCESS`? That controls
the exit code of your command, so you'll always want to have `Command::SUCCESS` at
the bottom of your command. If there was an error, you could `return Command::ERROR`.

***TIP
Whoops, the correct constant name if the command fails is `Command::FAILURE`!
***

Okay, let's try this! Head over to your terminal and run:

```terminal
php bin/console app:talk-to-me --yell
```

We get the output... and then we get:

> Do you want a mix recommendation?

Why, yes we *do*! And what an *excellent* recommendation!

All right, team! We did it! We finished - what I think is - the most important
Symfony tutorial of all time! No matter what you need to build in Symfony, the
concepts we've just learned will be the *foundation* of doing it.

For example, if you need to add a custom function or filter to Twig, no problem!
You do this by creating a Twig *extension* class... and you can use MakerBundle
to generate this for you or build it by hand. It's very similar to creating a
custom console command: in both cases, you're building something to "hook into"
part of Symfony.

So, to create a Twig *extension*, you would create a new PHP class, make it
implement whatever interface or base class that Twig extensions need (the
documentation will tell you that)... and then you just fill in the logic... which
I won't show here.

That's it! Behind the scenes, your Twig extension would *automatically* be seen as
a service, and autoconfiguration would make sure it's integrated into Twig...
*exactly* like the console command.

In the next course, we'll put our new superpowers to work by adding a database to
our app so that we can load real, dynamic data. And if you have any *real*,
*dynamic* questions, we are here for you, as always, down in the comment section.

All right, friends. Thanks so much for coding with me and we'll see you next time.

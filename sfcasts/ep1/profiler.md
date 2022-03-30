# Profiler: Your Debugging Best Friend

Time to install our *second* package. And this one is *fun*. Let's commit our
changes first: it'll makes it easier to check out any changes that the new package's
recipe makes.

Add everything:

```terminal-silent
git add .
```

That looks fine so... commit:

```terminal-silent
git commit -m "Added some Tiwggy goodness"
```

Beautiful.

## The debug Pack

*Now* run:

```terminal
composer require debug
```

So yes, this is *another* Flex alias... and apparently it's an alias for
`symfony/debug-pack`. And *we* know that a pack is a *collection* of packages. So
instead of adding this *one* line to our `composer.json` file, if we check,
it looks like it added one new package up under the `require` section - this
is a logging library - and... *all* the way at the bottom, it added a new
`require-dev` section with three *other* libraries.

The difference between `require` and `require-dev` isn't too important: all of these
packages were downloaded into our app, But as a best practice, if you install a
library that's only meant for *local* development, you should put it into
`require-dev`. The pack did that for us! Thanks pack!

## Recipe Changes

Back at the terminal, this also installed three recipes! Ooh. Let's see what those
did. I'll clear the screen and run:

```terminal
git status
```

So this is familiar: it modified `config/bundles.php` to activate three
new bundles. Again, bundles are Symfony plugins, which add more features to
our app.

It also added several configuration files to the `config/packages/` directory.
We will talk more about these files in the next tutorial, but, on a high level,
they control the *behavior* of those bundles.

## The Web Debug Toolbar And Profiler

So what *did* these new bundles give us? To find out, head over to your browser and
refresh the homepage. Holy cats, Batman! It's the web debug toolbar. This is debugging
*madness*: a toolbar *full* of good info. On the left, you can see the controller
that was called along with the HTTP status code. There's also the amount of time
the page took, the memory it used and also how many templates were rendered
through Twig: this is the cute Twig icon.

On the right side, we have details about the Symfony local web server that's running
and PHP info.

But you haven't seen the best part: click any of these icons to jump into the
*profiler*. This is the web debug toolbar... gone crazy. It's *full* of
data about that request, like the request and response, all of the log messages
that happened during that request, information about the routes and the route
that was matched, Twig shows you which templates were rendered and how many *times*
they were rendered... and there's configuration information down here. Phew!

But my *most* favorite section is Performance. This shows a timeline of everything
that happened during the request. This is great for two reasons. The first is
pretty obvious: you can use this to find which parts of your page are slow.
So, for example, our controller took 20.4 millisecond. And *within* the controller's
execution, the homepage template rendered in 3.9 milliseconds and `base.html.twig`
rendered in 2.8 milliseconds.

The second reason this is really cool is that it uncovers all the hidden layers
of Symfony. Set this threshold down to zero. Previously, this only displayed things
that took *more* than one millisecond. Now it's showing *everything*. You don't
need to worry about the vast majority of the stuff, but it's *super* cool to see
the layers of Symfony: the things that happen before and after your controller
is executed. We have a deep dive tutorial for Symfony if you want to learn more
about this stuff.

The web debug toolbar and profiler will also grow with our app. In a future
tutorial, when we install a library to talk to the database, we will suddenly have
a new section that lists all of the database queries that a page made and how long
each took.

## dump() and dd() Functions

Ok, so the debug pack installed the web debug toolbar. It also installed a logging
library that we'll use later. *And* it installed a package that gives us two fantastic
debug functions.

Head over to `VinylController`. Pretend that we're doing some developing and we
need to see what this `$tracks` variable looks like. It's pretty obvious in this
case, but sometimes you'll want to see what's inside a complex object.

To do that, say `dd($tracks)` where "dd" stands for dump and die.

[[[ code('841aecc2d1') ]]]

So if we refresh... yup! That dumps the variable and kills the page. And this is
a *lot* more powerful - and prettier - than using `var_dump()`: we can expand sections
and see deep data really easily.

Instead of `dd()`, you can also use `dump()`.. to dump and *live*. But this might
not show up where you expect it to. Instead of printing in the middle of the page,
it shows up down in the web debug toolbar, under the target icon.

[[[ code('13b985bb4b') ]]]

If that's a bit too small, click to see a bigger version in the profiler.

## Dumping in Twig

You can *also* use this `dump()` in Twig. Remove the dump from the controller...
and then in the template, right before the `ul`, `dump(tracks)`.

[[[ code('ee2dc39650') ]]]

And this... looks exactly the same. Except that when you dump in Twig, it
*does* dump right into the middle of the page

And even *more* useful, in Twig *only*, you can use `dump()` with no arguments.

[[[ code('43c4067dd7') ]]]

This will dump *all* variables that we have access to. So here's the `title` variable,
`tracks` and, surprise! There's a third variable called `app`. This is a global
variable that we have in *all* templates... and it gives us access to things like
the session and user data. And... we just discovered it by being curious!

So now that we've got these awesome debugging tools, let's turn to our next
job... which is to make this site less ugly. Time to add CSS and a proper
layout to bring our site to life!

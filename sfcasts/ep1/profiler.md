# Profiler: Your Debugging Best Friend

Time to install our *second* package. And this one is fun. But let's commit our
changes first: it'll makes it easier to check out any changes that the new package's
recipe makes.

Add everything:

```terminal-silent
git add .
```

That looks fine and... commit:

```terminal-silent
git commit -m "Added some Tiwggy goodness"
```

Beautiful. So let's install one of my *favorite* packages. Run:

## The debug Pack

```terminal
composer require debug
```

So yes, that is *another* Flex alias... and apparently it's an alias for
`symfony/debug-pack`. And *we* know what a pack is a *collection* of packages. So
instead of adding this *one* line to our `composer.json` file it, if we check,
it looks like it added one new package up here under the `require` section - this
is a logging library - and... *all* the way at the bottom, it added a new
`require-dev` section with three *other* libraries.

The difference between `require` and `require-dev` isn't too important: all of these
packages were downloaded into our app, But as a best practice, if you install a
library that's only meant for *local* development, you should put it into
`require-dev`. The pack did that for us! Thanks pack!

## Recipe Changes

Back at the terminal, this also installed three recipes. Ooh. Let's see what those
did. I'll clear the screen and run:

```terminal
git status
```

So this is kind of familiar: it modified `config/bundles.php` to activate three
new bundles. Again, bundles are Symfony plugins, which add more features to
our app.

It also added a number of configuration files in the `config/packages/` directory.
We will talk more about these files in the next tutorial, but, on a high level,
these control the behavior of those bundles.

## The Web Debug Toolbar And Profiler

So what *did* these new bundles give us? to find out, head over to your browser and
refresh the homepage. Holy cats, Batman! It's the web debug toolbar. This is debugging
*madness*: a toolbar bars *full* of good information. On the left, you can see
the controller that was called along with the HTTP status code. And it has other
data like the amount of time the page took, the memory it used and also how many
templates were rendered through Twig... this is the cute little Twig icon.

On the right side, we have details about the Symfony local web server that's running
and PHP info.

But you haven't seen the best part: you can click any of these icons to jump into
the *profiler*. This is like the web debug toolbar... gone crazy. It's *full* of
data about that request, like request and response data, all of the log messages
that happened during that request, information about the routes and the route
that was matched, Twig shows you which templates were rendered and how many *times*
they were rendered... there's configuration information down here and more.

My *most* favorite section is Performance. This shows us a timeline of everything
that happened during the request. This is so great for two reasons. the first is
pretty obvious: you can use this to find which parts of your page load were slow.
So, for example, our controller took 20.4 millisecond. And *within* the controller's
execution, the homepage template rendered in 3.9 milliseconds `base.html.twig`
rendered in 2.8 seconds milliseconds.

The second reason this is really cool is that it uncovers all the hidden layers
of Symfony. Set this threshold down to zero. Previously, this was only showing things
that took *more* than one millisecond. Now it's showing *everything*. You don't
need to worry about the vast majority of the stuff, but it's really cool to see
the layers of Symfony: the things that happen before and after your controller
is executed. We have a deep dive tutorial about Symfony if you want to learn more
about this stuff.

The web debug toolbar and profiler will also grow as our app grows. In a future
tutorial, when we install a library to talk in the database, we will suddenly have
a new section that lists all of the database queries that a page made and how long
each took.

## dump() and dd() Functions

Ok, so the debug pack installed the web debug toolbar. It also installed a logging
library that we'll use later. *And* it installed a package that gives us two fantastic
debug functions.

Head over to `VinylController`. Pretend that we're doing some developing and we
need to see what this `$tracks` variable looks like. It's pretty obvious in this
case, but sometimes you might want to see what's inside a complex object.

To do that, say `dd($tracks)` where "dd" stands for dump and die.

So if we refresh... yup! That dumps the variable and kills the page. And this is
a *lot* more powerful - and prettier - than using `var_dump()`: we can expand sections
and see deep data really easily.

Instead of DD, you can also use `dump()` to dump but *not* die. But this might
not show up where you expect it to. Instead of printing in the middle of the page,
it shows up down here in the web debug toolbar, under the target icon.

And if that's a bit too small, you can click that to see a bigger version in
the profiler.

## Dumping in Twig

You can *also* use this `dump()` inside of Twig. Remove the dump from the controller...
and then in the template, right before the `ul`, `dump(tracks)`.

And this... looks exactly the same. Oh, except that when you dump in Twig, it
*does* dump right into the middle of the page

And even *more* useful, in Twig only, you can use `dump()` with no arguments.
This will dump *all* variables that we have access to. So here's the `title` variable,
`tracks` and, surprise, there's a third variable called `app`. This is a global
variable that you have in *all* templates. It gives you access to things like the
session and user data. And we just discovered it was there by being curious!

So now that we've got these awesome debugging tools in place, let's turn to our next
job... which is to make this site less ugly. Time to add some CSS and a proper
layout to bring our site to life!

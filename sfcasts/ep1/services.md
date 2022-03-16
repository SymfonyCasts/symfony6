# Service Objects

I see Symfony as two big parts. The first is the route, controller, response system.
It's dead simple and well... you're already an expert on it! The second half of
Symfony is *all* about the many useful objects that are floating around... just
waiting for you to use them!

## Hello Service Objects

For example, when we render a template, what we are *actually* doing is taking
advantage of a Twig object and asking *it* to render a template. There's also a
logger object, a cache object, a database connection object, an object that helps
make API requests, and many, many more! And when you install a new bundle, that
give you even *more* useful objects.

The truth is that *every* single thing that *Symfony* does is... actually done by
one of these useful objects. Heck there's even a router object that's responsible
for finding the matching route for the given page!

In the Symfony world, and really the object oriented programming world in general,
these "objects that do work" have a special name: *services*. But don't let that
word confuse you. When you hear service, just think: that's an object that does
work! Like a templating object that renders template or a database connection object
that makes queries.

And since service objects do work, they're basically tools. The second half of
Symfony is *all* about discovering which services are available and how to use
them.

## The debug:autowiring Command

Let's try something. In our controller, I want to log a message... maybe some
debugging message. Since logging a message is work, it's done by a service. Does
our app already have a logger service? And if so, how do we get it?

To find out, move over to your terminal and run another `bin/console` command:

```terminal
php bin/console debug:autowiring
```

Say hello to one of the most *powerful* `bin/console` commands. I *love* this thing!
This lists *all* of the services that exist in our app. Okay, it's actually not the
*full* list, but this shows the services that you're most likely to need to use.
And even though our app is small, there's a lot of stuff here! There's a filesystem
service... And down here a cache service. There's even a twig service!

Is there a service for logging? You can look in this list... or you can re-run this
command and search for the word log:

```terminal-silent
php bin/console debug:autowiring
```

Excellent! For now, ignore everything except for the first line. This line tells
us that there *is* a logger service and that this object implements an interface
called `Psr\Log\LoggerInterface`.

## Fetching a Service via Autowiring

Ok, so why does knowing that help us? Because if you want a service, you *ask* for
it by using this interface as a type-hint. It's called autowiring.

Let me show you. Head over to our controller and add a second argument. Actually,
the order of these arguments don't matter. What matters is that this new argument
is *type-hinted* with `LoggerInterface`. I'll hit tab to autocomplete that, so
PhpStorm adds the use statement on top.

In this case, the argument can be *called* anything, like `$logger`. When Symfony
sees this type-hint, it looks inside of the `debug:auto-wiring` list... and because
there's a match, it will pass us the logger service.

So we now know *two* different types of arguments that we are allowed to have in
controller. First, you can have an argument whose *name* matches a wildcard
in the route. Or second, you can have an argument whose type-hint matches one
of the services in our app.

Ok, so now that we know Symfony will pass us the logger service object, let's use
it! I don't know, yet, what *methods* I can call on this but... if we say
`$logger->`... PhpStorm... tells us! That was easy!

I'm going to log something at an `info()` priority level. Let's say:

> Returning API response for song

And then the `$id`. Actually, we can do something even cooler with this logger
service. add `{song}` to the message... and pass this as a second argument, which
is extra information you want to attach to the log message. Pass `song` set to
`$id`. In a minute, you'll see that the logger will print the *actual* id in place
of the `{song}`.

*Anyways*, this controller is for our API endpoint. So let's go over and refresh.
Um.. ok, so no error, that's good! But did it work? Where does the logger service...
actually log to?

Let's find out next, learn a trick to see the profiler even for API requests and
*also* leverage our second service directly.

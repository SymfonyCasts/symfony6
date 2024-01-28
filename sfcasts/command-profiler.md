# Profiling Commands

In the dev environment on our site, we get the web debug toolbar. And more
importantly, the profiler, which is packed full of goodies. Even if our app is
entirely an API, we can go directly to `/_profiler` to check out the profiler
for any API request.

This is one of Symfony's killer features. And for 6.4, Symfony contributor
[Jules Pietri](https://twitter.com/julespietri) wondered: why can't we have this
for console commands?

And now, we do! It's meant to be used for your custom console commands that might
be big or complex, but we can also use it with *core* commands.

## Triggering a Profile: --profile

Spin over and run:

```terminal
php bin/console debug:container
```

If you run a normal command, it won't activate the profiler system and collect
info. To trigger that, you need to run the command with `--profile`.

```terminal-silent
php bin/console debug:container --profile
```

Nothing *looks* different, but that *did* just activate the profiler... which
collected info and stored it... somewhere. But... it's not obvious where we can go
to see it!

So what you really want to do is pass `-v`:

```terminal-silent
php bin/console debug:container --profile -v
```

Now, at the bottom, it includes the unique token that can be used in the profiler
URL. But, really, be lazier and run with `-vvv`:

```terminal-silent
php bin/console debug:container --profile -vvv
```

This time, we get a *link* - and even details about memory and time. I'll
click the link and... it doesn't work. It's *almost* the right URL, but my terminal
doesn't know what port my local web server is using. Copy that token, then...
go to the profiler for any request, paste the token in the URL and... so cool!

## Exploring the Profiler

We see info about the command, the input, output... and most importantly,
we have the normal profiler sections! One interesting one is events: showing the
actual events that were dispatched and the listeners for each one. These are
*totally* different from the events that are triggered during a request, so it's
cool to see them.

Now, you probably noticed that most of the profiler sections are grayed out. But
if you *did* render a Twig template... or make an HTTP request or make a database
query, these *would* be activated.

Even with this simple command, we unlock the performance section. Not a lot here
in *this* case, but it makes me feel dangerous.

So that's it! Another, cool, well-thought-out feature. I'd love to see how people
end up using this.

Ok, on to our final topic: let's experiment with one of Symfony's best new components:
Scheduler.

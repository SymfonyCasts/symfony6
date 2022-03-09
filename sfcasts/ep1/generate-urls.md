# Generate Urls & bin/console

There are two different ways that we can interact with our app. The first is
via the web server... and that's what we've been doing! We got to a URL and...
behind the scenes, this executes the `public/index.php` file, which boots up Symfony,
calls the routing and executes our controller.

## Hello bin/console

What's the *second* way we can interact with our app? We haven't seen it yet: it's
via a command line tool called `bin/console`. At your terminal run:

```terminal
php bin/console
```

... to see a *bunch* of commands *within* this script. I *love* this thing. It's
full of commands to help us debug, eventually it will have code-generation commands,
commands for setting secrets: all kinds of good stuff that we're going to discover
little-by-little.

But I want you to notice that there's nothing special about this `bin/console` script.
It's just a file: there's literally a `bin/` directory with a `console` file inside.
You'll probably never need to open this file or think about it, but it *is* useful.
Oh, and on most systems, you can just run:

```terminal
./bin/console
```

Or sometimes you may see me run:

```terminal
symfony console
```

Which is just *another* way to execute this file that we'll talk more about in
a future tutorial.

## bin/console debug:router

The *first* command I want to check out inside of `bin/console` is `debug:router`:

```terminal-silent
php bin/console debug:router
```

This is awesome. It shows us *every* route in our app, like our two routes
for `/` and `/browse/{slug}`. What are these other routes? These come form the
web debug toolbar and profiler system... and they're only here while we're
developing locally.

Ok, back on our site.... at the top of the page, we have two non-functional links
to the homepage and browse page. Let's hook these up. Open `templates/base.html.twig`...
and search for `a` tags. Here we go.

So it would be *really* easy to get this working by just `href="?"`. But instead,
whenever we link to a page in Symfony, we are going to ask the routing system to
*generate* a URL for us. We'll say:

> Please generate the URL to the homepage's route, or the browse page's route.

*Then*, if we ever change the URL on the route, all our links will instantly update.
Magic.

## Naming your Route

So let's start with the homepage. How do we ask Symfony to generate a URL to this
route? Well, first, we need to give the route a *name*. Surprise! *Every* route has
an internal name. You can see it back at `debug:router`. Yup, our route's are
named `app_vinyl_homepage` and `app_vinyl_browse`. Those would make *great* names
for a pair of cats.

And where did these names come from? By default, Symfony generates a name *for*
you, which is fine. The name isn't used at *all* until you generate a URL to it.
And as soon as you *do* need to generate a URL to a route, I highly recommend
taking control of this name... just to make sure it never accidentally changes.

To do this, find the route and add an argument: `name` set to, how about,
`app_homepage`. I like using the `app_` prefix: it makes it easier to search
for a route name later.

By the way, PHP 8 attributes - like this `Route` attribute - are represented by
actual, physical PHP classes. If you hold command or ctrl, you can open it and look
inside. And this is great: the `__construct()` method shows all of the different
options you can pass to the attribute.

For example, there's a `name` argument... and then we're using PHP's named
argument syntax to pass this in the attribute. Opening up an attribute is a
*great* way to learn about all the available options.

## Generating a URL from Twig

Anyways, now that we've given this name, go back to our terminal and run
`debug:router` again:

```terminal-silent
php bin/console debug:router
```

This time... yea! The route is named `app_homepage`! Ok, copy that, then head back
over to `base.html.twig`. To generate a URL inside of twig, say `{{` - because
we're going to print something - and then use a Twig function called `path()`.
Pass this the route name.

That's it. Refresh... and the link up here works!

One more link to go. We know step one: give our route a name. So `name:` and, how
about, `app_browse`.

Copy that, and... scroll down a bit. Here it is: "Browse Mixes". Change that
to `{{ path('app_browse') }}`.

And now... that link works too!

## Generating URLs with Wildcards

Oh, but on this page, we have some quick links to go to the browse page for a specific
genre. And these do *not* work yet.

This is interesting. We want to generate a URL like before... but this time we need
to pass something to this `{slug}` wildcard. Open `browse.html.twig`. Here's how
you do that. The first part is the same: `{{ path() }}` and then the name
of our: `app_browse`.

If we just stopped here, it would generate `/browse`. To pass values to any wildcards
in the route, `path()` has a second argument: an associative array of those value.
And, again, just like JavaScript, to create an "associative array", you use
`{` and `}`. I'm you're going to hit enter to break this onto multiple lines... just
to keep things readable. Inside add a `slug` key to the array... and since this is
the "Pop" genre, set this to `pop`.

And... I'll repeat this a few more times: `{{ path('app_browse') }}`. pass curly
braces for an associative array, with `slug` set to `rock`. And then one more time
down here... which I'll do really quickly.

Ok, let's see if it works! Refresh. Ah, an error! Variable `rock` doesn't exist.
I bet some of you you saw me do that. I forgot my quotes, so this looks like a
*variable*.

Try it again. There we go. And try the links... yes! They work perfectly!

Next: we've created two HTML pages. Now let's see what it looks like to create
a JSON API endpoint.

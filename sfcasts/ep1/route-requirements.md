# Route Requirements

Now that we have a new page, at your terminal, run `debug:router` again.

```terminal-silent
php bin/console debug:router
```

Yep, there's our new endpoint! Notice that the table has a column called "Method"
that says "any". This means that you can make a request to this URL using *any*
HTTP method - like GET or POST - and it will match that route.

## Restricting Routes to GET or POST Only

But the purpose of our new API endpoint is to allow users to make a GET request to
fetch song data. Technically, right now, you could also make a POST for request
to this... and it would work just fine. We might not care, but often with APIs,
you'll want to restrict an endpoint to only work with one specific method like
GET, POSt or PUT. Can we make this route somehow only match GET requests?

Yep! By adding another option to the `Route` In this case, it's called `methods`,
it event auto-completes! Set this to an array and, put `GET`.

I'm going to hold Command and click into the `Route` class again... so we can see
that... yup! `methods` is one of the arguments.

Back over on `debug:router`:

```terminal-silent
php bin/console debug:router
```

Nice. The route will now only match GET requests. It's... kind of hard to test this,
since a browser always makes GET requests if you go directly to a URL... but this is
where *another* `bin/console` comes in handy: `router:match`.

And if we run this with no arguments:

```terminal-silent
php bin/console router:match
```

It gives us an error, but it shows how it's used. Try:

```terminal
php bin/console router:match /api/songs/11
```

And... it will tell you that it matches our new route!. But *now* ask it what
would happen if we made a POST request to that URL with `--method=post`:

```terminal-silent
php bin/console router:match /api/songs/11
```

No routes match this path with that method! But it *does* say that it *almost*
matched our route.

## Restricting Route Wildcards by Regex

Let's do *one* more thing to tighten up our new endpoint. I'm going to add
an `int` type-hint to the `$id` argument.

That... doesn't change anything, except that PHP will now take the string `id` from
the URL that Symfony passes into this method and cast it into an `int`, which is...
just nice because then we're dealing with a true integer in our code.

You can see the subtle difference in the response. Right now, when we return our
`JsonResponse`, the `id` field is a string. When we refresh, ah, `id` is now a
true number in JSON.

But... if somebody was being tricky... and went to `/api/songs/apple`... yikes!

A PHP error, which, on production, would be a 500 error page! We do not want that.

But... what can we do? The error comes comes from when Symfony tries to call our
our controller and pass in that argument. So it's not like we can put code down
*in* the controller to check if `$id` is a number: it's too late!

What if, instead, we could tell Symfony that this route should only *match* if
the `id` wildcard is a *number*. Is that possible? Totally!

By default, when you have a wildcard, it matches anything. But you *can* change it
match a custom *regular expression*. Inside of the curly braces, right after the
name, add a `<` then `>` and, in between, `\d+`. That's a regular expression
meaning "a digit of anything length".

Let's try it! Refresh and... yes! A 404. No route found: it simply didn't match
this route. A 404 error is great... but the 500 error was not. And if we head back
to `/api/songs/5`... that still work.

Next: if you asked me what *the* most central and super important part of Symfony
is, I wouldn't hesitate: it's services. Let's find out what a service and how it's
the key to unlocking Symfony's potential.

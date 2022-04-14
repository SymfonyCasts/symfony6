# Wildcard Routes

The homepage will eventually be the place where a user can design and build their
next sweet mix tape. But in addition to *creating* new tapes, users will also be able
to browse *other* people's creations.

## Creating a Second Page

Let's make a second page for that. How? By adding a second controller: public
function, how about `browse`: the name doesn't really matter. And to be responsible,
I'll add a `Response` return type.

Above this, we need our route. This will look exactly the same, except set
the URL to `/browse`. Inside the method, what do we *always* return from a
controller? That's right: a `Response` object! Return a new `Response`... with a
short message to start.

[[[ code('00c844ab6c') ]]]

Let's try it! If we refresh the homepage, nothing changes. But if we go
to `/browse`... we're crushing it! A second page in under a minute! Dang!

On this page, we'll eventually list mix tapes from other users. To help find
something we like, I want users to *also* be able to browse by *genre*. For example,
if I go to `/browse/death-metal`, that would show me all the death metal vinyl
mix tapes. Hardcore.

Of course, if we try this URL right now... it doesn't work.

> Not Route found

No matching routes were found for this URL, so it shows us a 404 page. By the way,
what you're seeing is Symfony's fancy exception page, because we're currently
*developing*. It gives us *tons* of details whenever something goes wrong. When
you eventually deploy to production, you can design a *different* error page that
your users would see.

## {Wildcard} Routes

Anyways, the simplest way to make this URL work is just... to change the URL to
`/browse/death-metal`. 

[[[ code('7cf6861477') ]]]

But... not super flexible, right? We would need one route
for *every* genre... which could be hundreds! And also, we just killed the `/browse`
URL! *It* now 404's.

What we *really* want is a route that match `/browse/<ANYTHING>`. And we can do that
with a *wildcard*. Replace the hard-coded `death-metal` with `{}` and, inside,
`slug`. Slug is just a technical word for a "URL-safe name". Really, we could have
put anything inside the curly-braces, like `{genre}` or `{coolMusicCategory}`: it
makes no difference. But *whatever* we put inside this wildcard, we are then
*allowed* to have an argument with that same name: `$slug`.

[[[ code('5a1436e579') ]]]

Yup, if we go to `/browse/death-metal`, it will match this route and pass the string
`death-metal` to that argument. The matching is done by name: `{slug}` connects
to `$slug`.

To see if it's working, let's return a different response: `Genre` then the `$slug`.

[[[ code('90a1e7b05e') ]]]

Testing time! Head back to `/browse/death-metal` and... yes! Try `/browse/emo` and
yea! I'm *that* much closer to my Dashboard Confessional mix tape!

Oh, and it's optional, but you can add a `string` type to the `$slug` argument.
That doesn't change anything... it's just a nice way to program: the `$slug` was
*already* always going to be a string.

[[[ code('dd04d150f1') ]]]

A bit later, we'll learn how you could turn a *number* wildcard - like the 
number 5 - into an integer if you want to.

## Using Symfony's String Component

Let's make this page a bit fancier. Instead of printing out the slug exactly,
let's convert it to a title. Say `$title = str_replace()` and replace any dashes
with spaces. Then, down here, use title in the response. In a future tutorial,
we're going to query the database for these genres, but, for right now, we can
at least make it look nicer.

[[[ code('2d1891ae08') ]]]

If we try it, Emo doesn't look any different... but death metal *does*.
But I want *more* fancy! Add another line with `$title =` then
type `u` and auto-complete a function that's literally called... `u`.

We don't use many functions from Symfony, but this is a rare example. This comes
from a Symfony library called `symfony/string`. As I mentioned, Symfony is many
different libraries - also called components - and we're going to leverage those
libraries all the time. This one helps you make string transformations... and it
happens to already be installed.

Move the `str_replace()` to the first argument of `u()`. This function
returns an object that we can then do string operations on. One method is
called `title()`. Say `->title(true)` to convert all words to title case.

[[[ code('6cd92168f6') ]]]

Now whe n we try it... sweet! It uppercases the letters! The string component isn't
particularly important, I just want you to see how we can already leverage
parts of Symfony to get our job done.

## Making the Wildcard Optional

Ok: one last challenge. Going to `/browse/emo` or `/browse/death-metal` works.
But just going to `/browse`... does *not* work. It's broken! A wild card can
match anything, but, by default, a wild card is *required*. We *have* to go to
`/browse/<something>`.

Can we make a wildcard optional? Absolutely! And it's delightfully simple: make the
corresponding argument optional.

[[[ code('9ba3296dd1') ]]]

As soon as we do that, it tells Symfony's routing layer that the `{slug}` does not
need to be in the URL. So now when we refresh... it works. Though, that's not a great
message for the page.

Let's see. *If* there's a slug, then set the title the way we were. Else, set
`$title` to "All genres". Oh, and move the "Genre:" up here... so that down in
the `Response` we can just pass `$title`.

[[[ code('8cef2cd6cb') ]]]

Try that. On `/browse`... "All Genres". On `/browse/emo`... "Genre: Emo".

Next: putting text like this into a controller.... isn't very clean or scalable,
especially if we start including HTML. Nope, we need to render a template.
To do *that*, we're going to install our first third-party package and witness the
massively important Symfony recipe system in action.

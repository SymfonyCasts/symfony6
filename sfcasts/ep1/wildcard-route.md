# Wildcard Routes

The homepage will eventually be the place where a user can design and build their
next sweet mix tape. But in addition to creating mix tapes, users will also be able
to browse *other* people's creations.

## Creating a Second Page

Let's create a second page for that. How? By adding a second controller: public
function, how about browse: the name doesn't really matter. And to be responsible,
I'll add `Response` return type.

And above this, we need our route. This will look exactly the same, except that this
set the URL to `/browse`. Inside the method, what do we *always* return from a controller?
That's right: a `Response` object. Return a new `Response`... with a short message
to start.

Ok, let's try it! If we refresh the homepage, nothing changes here. But if we go
to `/browse`... we're crushing it! A second page in under a minute! Dang!

On this page, we'll eventually list mix tapes from other users. But to help find
something we like, I want users to *also* be able to browse by *genre*. For example,
if I go to `/browse/death-metal`, that would show me all the death metal mix takes.
That's hardcore.

Of course, we if try this URL right now, we see:

> Not Route found

No matching routes were found, so it shows us a 404 page. By the way, what you're
seeing here is Symfony's fancy exception page, because we're currently *developing*.
It gives us lots of details whenever something goes wrong.

## {Wildcard} Routes

Anyways, the simplest way to make this URL work is just... to change the URL to
`/browse/death-metal`. But... that's not very flexible? We would need one route for
*every* genre which could be hundreds! And also, we just killed the `/browse`
URL! It now is a 404.

What we *really* want is a route that match `/browse/{ANYTHING}`. And we can do that
with a *wildcard*. Replace the hard-coded `death-metal` with `{}` and, inside,
`slug`. Slug is just a technical word for a "URL safe name". And we could have put
anything inside the curly-braces, like `{genre}` or `{coolMusicCategory}`: it
makes no difference. But *whatever* we put inside of this wildcard, we are then
*allowed* to have an argument with that same name: `$slug`.

Yup, if we go to `/browse/death-metal`, it will match this route and pass the string
`death-metal` to that argument. The matching is done by name.

To see if it's working, let's return a different response: `Genre` then the `$slug`.

Testing time! Head back to `/browse/death-metal` and... yes! Try `/browse/emo` and
that works too!

Oh, and  it's optional, but you can add a `string` type to the `$slug` argument.
That does not change anything, it's just a nice way to program: the `$slug` was
*already* always going to be a string. A bit later, we'll learn how you could turn
a *number* wildcard - like the number 5 into an integer if you want to.

## Using Symfony's String Component

Let's make this page a bit fancier. Instead of printing out the slug exactly,
let's convert this to a title. Say title = `str_replace()` and replace any dashes
with spaces. Then down here, use title in the response. In a future tutorial,
we're going to query the database for these genres, but, for right now, we can
at least make it look nicer.

If we try it, Emo doesn't look any different... but the death metal page *does*.
But I want *more* fancy! Check this out: add another line with `$title =` then
type `u` and then auto-complete a function that's literally called... `u`.

We don't use many functions from Symfony, but this is a rare example. This comes
from a Symfony called `symfony/string`. As I mentioned, Symfony is many different
libraries - also called components - and we're going to leverage those libraries
all the time. This library helps you make string transformations and it happens
to already be installed.

Let's move the `str_replace()` to the first argument of `u()`. This function
returns an object that we can then do string operations on. One method is
called `title()`. Say `->title(true)` to convert all words to title case.

Now when we try it... sweet! It uppercases the letters! The string component isn't
particularly important, I just you to see how we can already leverage other parts
of Symfony to get your job done. Symfony is just a bunch of tools there to help.

## Making the Wildcard Optional

Ok: one last challenge. Going to `/browse/emo` or `/browse/death-metal` works.
But just going to `/browse`... does *not* work. It's broken! A wild card can
match anything, but, by default, a wild card is *required*. We have to go to
`/browse/<something>`.

Can we make a wildcard optional? Absolutely! And it's delightfully simple: make the
corresponding argument optional.

As soon as we do that, it tells Symfony's routing layer that the `{slug}` does not
need to be in the URL. So now when we refresh... it works. Though, that's not a great
message for this page.

Let's do a bit more work here. If there's a slug, then we will set the title the
way we were. Else,  set title to "All genres". Oh, and move the "Genre:" up here...
so that down in the `Response` we can just pass `$title`.

Try that. On `/browse`... "All Genres". Over `/browse/emo`... "Genre: Emo"

Next: putting text like this into a controller.... isn't very clean or scalable,
Especially if we start including HTML. Nope, we need to render a template.
To do *that*, we're going to install our first third-party package and witness the
massively important Symfony recipe system in action.

# Routes, Controllers & Responses

I gotta say, I miss the 90's. Well, not the beanie babies and... definitely not
how *I* dressed then, but... the mix tapes. If you weren't a kid in the 90's, you
may not know how hard it was to share your favorite tunes with your friends. Oh
yea, I'm talking about a Michael Jackson, Phil Collins and Paula Abdul mashup.
Perfection.

To capitalize off of that nostalgia, but with a hipster twist, we're going to create
a brand new app called Mixed Vinyl: a store where users can create mixed tapes -
complete with Boyz || Men, Mariah Carey and Smashing Pumpkins... except pressed onto
a vinyl record. Hmm, I might need to put a record player into my car.

The page we're looking at, which is super cute and changes colors when we refresh...
is not a real page. It's just a way for Symfony to say "hi" and link us to the
documentation. And by the way, the Symfony's documentation is great. So definitely
check it out as you're learning.

## Routes & Controllers

Ok: *every* web framework in *any* language has the same job: to help us create pages,
whether those are HTML pages JSON API responses. And pretty much every framework
does this in the same way: via a route & controller system. The route defines the
URL to a page and points to a controller. The controller is a PHP function that
builds that page.

So route + controller = page. It's math people.

## Creating the Controller

We're going to build these two things... kind of in reverse. So first, let's create
the controller function. In Symfony, the controller function is always a *method*
inside of a PHP class. I'll show you: in the `src/Controller/` directory, create
a new PHP class. Let's call it `VinylController`, but the name could be anything.

And, congrats! It's our first PHP class! And guess where it lives? In the `src/`
directory, because *all* PHP classes will live in `src/`. And for the most part,
it doesn't matter how you organize things inside `src/`: you can put things into
whatever directory you want and call the classes whatever you want. So flex your
creativity.

But there *are* two important rules to follow. First, notice the namespace that
PhpStorm added on top of the class: `App\Controller`. *However* you decide to organize
your `src/` directory, the namespace of a class *must* match the directory structure...
starting with `App`. You can imagine that the `App\` namespace points to the
`src/` directory. Then, if you put a file in a `Controller/` sub-directory, it
needs a `Controller` part in its namespace.

If you ever mess this up, like you typo something or leave off, you're gonna have a
bad time. PHP will not be able to find the class and you'll get a "class not found"
error. Oh, and the *other* rule is that the *name* of a file must match the class
name inside of it, plus `.php`. Hence, `VinylController.php`. We'll follow those
two rules for *all* files we create in `src/`.

## Creating the Controller

*Anyways*: back to our job of creating a controller function. Inside, add a new
public method called `homepage()`. And no, the name of this method doesn't matter
at all: you can name it after your cat if you want.

For now, I'm just going to put a `die()` statement with a message.

## Creating the Route

Ok, good start! Now that we have a controller function, let's create a route,
which defines the *URL* to our new page and points to this controller. There are
a few ways to create routes in Symfony, but pretty much everyone uses attributes.

Here's how it works. Right above this method, say `#[]`: this is the PHP 8 attribute
syntax, which is a way to add configuration to your code. Start typing `Route`.
But before you finish that, notice that PhpStorm is auto-completing that. Hit tab
to let it finish.

That, *nicely*, finished the word `Route` for me. But *more* importantly, it added
a `use` statement on top. Whenever you use an attribute, you *must* have a
corresponding `use` statement for it at the top of the file.

Inside `Route`, pass `/`, which will be the URL to our page.

And... done! This route defines the URL and points to this controller... simply
because it's right *above* that controller.

Let's try it! Refresh and... congratulations! Symfony looked at the URL, saw
that it matched the route - `/` or no slash is the same for the homepage - executed
our controller, which hit the `die` statement!

Oh, and by the way, I keep saying controller function. That's commonly just called
the "controller" or the "action"... just to confuse things.

## Returning a Response

Ok, so inside of the controller - or action - we can write *whatever* code we want
to build the page, like make database queries, API calls, render a template, whatever.
We are going to do all of that eventually.

The *only* thing that Symfony cares about is this that your controller returns a
`Response` object. Check it out: type `return` and then start typing `Response`.
Woh: there are quite a few `Response` classes already in our code... and two
are from Symfony! We want the one from HTTP foundation. HTTP foundation is one
of those Symfony libraries, and it gives us nice classes for things like the Request,
Response and Session. Hit tab to auto-complete and finish that.

Oh, I should have said *return* new response. That's better. *Now* hit tab. When
I let `Response` auto-complete the first time, *very* importantly, PhpStorm
added this use statement on top. *Every* time we reference a class or interface,
we will need to add a `use` statement to the top of the file we're working in.

By letting PhpStorm auto-complete that for me, it added the `use` statement
automatically. I'll do that *every* time I reference a class. Oh, and if you're
still a bit new to PHP namespaces and `use` statements, check out our short and
free PHP namespaces tutorial.

Anyways, inside of `Response`, we'll put whatever we want to return to the user:
HTML, JSON or, for now, a simple message, like the title of the Mixed vinyl we're
working on: PB and jams.

Ok team, let's see what happens! Refresh and... PB and Jams! It may not like much
yet, but we just built our first fully-functional Symfony page! Route + controller
profit.

Congrats: you've already learned the most *foundational* part of Symfony... and we're
just getting started. Oh, and because our controllers always return a `Response`
object, it's optional, but you can add a return type to this function if you want
to. That doesn't change anything, it's just a nice way to code.

Next I'm feeling pretty confident. So let's create *another* page, but with a much
fancier route that matches a wildcard pattern.

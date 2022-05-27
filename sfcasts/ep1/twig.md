# Twig ❤️

Symfony controller classes do *not* need to extend a base class. As long as your
controller function returns a `Response` object, Symfony doesn't care *what* your
controller looks like. But usually, you *will* extend a class called
`AbstractController`.

Why? Because it gives us shortcut methods.

## Rendering a Template

And the *first* shortcut is `render()`: the method for rendering a
template. So return `$this->render()` and pass it two things. The first
is the name of the template. How about `vinyl/homepage.html.twig`.

It's not required, but it's common to have a directory with the same
name as your controller class and filename that's the same as your method,
but you can do whatever. The second argument is an array of any variables
that you want to pass *into* the template. Let's pass in a variable called
`title` and set it to our mix tape title: "PB and Jams".

[[[ code('28e791440a') ]]]

Done in here. Oh, but pop quiz! What do you think the `render()` method returns?
Yea, it's the thing I *keep* repeating: a controller must always return a `Response`
object. `render()` is just a shortcut to render a template, get that string and put
it into a `Response` object. `render()` returns a `Response`.

## Creating the Template

We know from earlier that when you render a template, Twig looks in the `templates/`
directory. So create a new `vinyl/` sub-directory... and inside of that, a file
called `homepage.html.twig`. To start, add an `h1` and then print the `title` variable
with a special Twig syntax: `{{ title }}`. And... I'll add some hardcoded TODO text.

[[[ code('285607e2a3') ]]]

Let's... go see if this works! We were working on our homepage, so go there and...
hello Twig!

## Twigs 3 Syntax

Twig is one of the *nicest* parts of Symfony, and also one of the easiest. We're
going to go through everything you need to know... in basically the next ten minutes.

Twig has exactly *three* different syntaxes. If you need to print something,
use `{{`. I call this the "say something" syntax. If I say `{{ saySomething }}`
that would print a variable called `saySomething`. Once you're *inside* Twig,
it looks a *lot* like JavaScript. For example, if I surround this in quotes, now
I'm printing the *string* `saySomething`. Twig has functions... so that
would call the function and print the result.

So syntax #1 - the "say something" syntax - is `{{`

The second syntax... doesn't really count. It's `{#` to create a comment... and
that's it.

[[[ code('285607e2a3') ]]]

The *third* and final syntax I call the "do something" syntax. This is when you're
not *printing*, your *doing* something in the language. Examples of "doing something"
would be if statements, for loops or setting variables.

## The for Loop

Let's try a `for` loop. Go back to the controller. I'm going to paste in
a tracks list... and then pass a `tracks` variable into the template set to that array.

[[[ code('8123e02600') ]]]

Now, unlike `title`, tracks is an array... so we can't just print it. But, we
can try! Ha! That gives us an array to string conversion. Nope, we need to *loop*
over tracks.

Add a header and a `ul`. To loop, we'll use the "do something" syntax, which is
`{%` and then the *thing* that you want to do, like `for`, `if` or `set`. I'll show
you the full list of do something tags in a minute. A for loop looks like this:
`for track in tracks`, where tracks is the variable we're looping over and `track`
will be the variable *inside* the loop.

After this, add `{% endfor %}`: most "do something" tags have an end tag. *Inside*
the loop, add an `li` and then use the say something syntax to print `track`.

[[[ code('dc265ad487') ]]]

## Using Sub.keys

When we try it... nice! Oh, but let's get *trickier*. Back in the controller,
instead of using a simple array, I'll restructure this to make each track an
associative array with `song` and `artist` keys. I'll paste in that same change
for the rest.

[[[ code('647cdcfd7e') ]]]

What happens if we try it? Ah, we're back to the "array to string" conversion.
When we loop, each track *itself* is now an array. How can we read the `song`
and `artist` keys?

Remember when I said that Twig looks a lot like JavaScript? Well then, it shouldn't
be a surprise that the answer is `track.song` and `track.artist`.

[[[ code('fc54d0388b') ]]]

And... *that* gets our list working.

Now that we have the basics of Twig down, next, let's look at the *full* list
of "do something" tags, learn about Twig "filters" *and* tackle the all-important
template inheritance system.

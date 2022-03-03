# Twig ❤️

Symfony controller classes do *not* need to extend some base class. As long as your
controller function returns a `Response` object, Symfony doesn't care *what* your
controller looks like. But usually, you *will* extend a base class called
`AbstractController`.

Why? Because it gives us shortcut methods.

## Rendering a Template

And the *first* shortcut method is the `render()`: the method for rendering a
template. So return `$this->render()` and pass this two things. The first
is the name of the template. How about `vinyl/homepage.html.twig`.

It's not a requirement, but it's very common to have a directory with the same
know as your controller class and filename that's the same name as your method,
but you can do whatever you want. The second argument is an array of any variables
that you want to pass *into* the template. Let's pass in a variable called
`title` and set it to our mix tape title: "PB and Jams".

Done in here. Oh, but pop quiz! What do you think `render()` method returns? Yea,
it's the thing I *keep* repeating: a controller must always return a `Response` object.
`render()` is just a shortcut to render a template, get that string and put
it into a `Response` object. `render()` returns a `Response`.

## Creating the Template

We know from earlier that when you render a template, twig looks in the `templates/`
directory. So create a new sub-directory `vinyl/`... and inside of that, a new file
called `homepage.html.twig`. Inside, add an `h1` and then print the `title` variable
with a special Twig syntax: `{{ title }}`. And... I'll put some hardcoded TODO text.

Let's... go see if this works! We were working on our homepage, so go there and...
hello Twig!

## Twigs 3 Syntax

Twig is one of the *nicest* parts of Symfony, and also one of the easiest. We're
going go through everything you need to know... in basically the next five minutes.

Twig has exactly *three* different syntaxes. If you need to print something,
you use `{{`. I call this the "say something" syntax. If I say `{{ saySomething }}`
that would be print a variable called `saySomething`. Once you're *inside* Twig,
it looks a lot like JavaScript. For example, if I surround this in quotes, now
I'm printing the *string* `saySomething`. Twig has has functions... so that
would call the function and print the result.

So syntax #1 - the "say something" syntax is `{{`

The second syntax... doesn't really count. It's `{#` to create a comment. It's
the sample.

The *third* and final syntax I call the "do something" syntax. This is when you're
not *printing*, your *doing* something in the language. Examples of "doing something"
would be if statements, for loops or setting variables.

## The for Loop

Let's actually try a `for` loop. Go back to the controller. I'm going to paste in
a tracks list. And then pass a `tracks` variable into the template set to that array.

Now, unlike `title`, since tracks is an array, we can't just print it. That'll
give us an array to string conversion. Nope, we need to *loop* over it.

Add a header and a `ul`. To loop, we'll use the "do something" syntax, which is
`{%` and then the *thing* that you want to do, like `for`, `if` or `set`. I'll show
you the full list of do something tags in a minute.  A for loop looks like this:
`four track in tracks`, where tracks is the variable we're looping over and `track`
will be the variable inside the loop.

After this, add `{% endfor %}`: most "do something" tags have an end. *Inside*
the loop, add an `li` and then use the say something tag to print `track`.

## Using Sub.keys

When we try it... nice! Oh, but let's get even *trickier*. Back in the controller,
instead of using a simple array, I'll restructure this to make each track an
associative array with `song` and `artist` keys. I'll paste in that same change
for the rest.

What happens if we try it! Ah, we're back to the "array to string" conversion.
When we loop over these tracks, each track *itself* is now an array. How can we
read the `song` and `artist` keys?

I mentioned that Twig looks a lot like JavaScript. So it shouldn't be a surprise
that we can say `track.song` and `track.artist`.

And... *that* gets our list working.

Now that we have the basics of Twig down, next, let's look at the *full* list
of "do something" tags, learn about Twig "filters" *and* tackle the all-important
template inheritance system.

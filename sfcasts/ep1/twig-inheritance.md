# Twig Inheritance

Head to https://twig.symfony.com... and then click to check its documentation. There's
lots of good stuff here. But what I want you to do is scroll down to the Twig
reference. Yea!

## Tags

The first things to look at, on the left, are these things called tags. This
list represents *every* possible thing you can use with the do something syntax. Yup,
it will always be `{%` and then *one* of these things, like `for` or `if`. And
honestly, you're only going to use about 5 of these on an everyday basis. If
you want to know the syntax for one of these, just click to see its docs.

## Filters

In addition to the 20 tags, Twig also has something called *filters*. These are sweet.
Filters are basically functions, but with a more hipster syntax. Twig *does* also
have functions, but there are fewer: Twig really prefers filters: they're way
cooler!

For example, there's a filter called `upper`. Using a filter is like using the
`|` key on the command line. You have some value - then you "pipe it into"
the filter you want, like `upper`.

Let's try this! Print `track.artist|upper`.

[[[ code('73974377cd') ]]]

And now... it's uppercase! If you want to confuse your coworkers, you can pipe
*that* to `lower`... which sends things *back* to lowercase. There's no *real*
reason to do this, but filters *can* be chained like this.

[[[ code('016bee2e4d') ]]]

Anyways, check out the filters list because there's probably something you'll
find useful.

And... that's pretty much it! Beyond functions, there's also something called
"tests", which are handy in if statements: you can say things like "if number is
divisibleby 5".

## Template Inheritance

Ok, just *one* more thing to learn about Twig, and it's cool.

View the HTML source of the page. Notice that there is *no* HTML structure: there's
no `html`, `head` or `body` tags. Literally the HTML that we have inside of our
template, is what we get. Nothing more.

So is there... some sort of layout system in Twig where we can add a base layout
around us? Absolutely. And it's incredible. It's called template inheritance. If
you have a template and you want that to use some base layout, at the very top of
the file, use a "do something" tag called `extends`. Pass this the name of the
layout file: `base.html.twig`.

[[[ code('48dc6d8fec') ]]]

That's referring to this template right here. Before we check that out, if we
try this now, yikes! Big error:

> A template that extends another cannot include content outside Twig blocks.

To figure out what that means, open `base.html.twig`. This is your base layout file...
and you're *totally* free to customize it however you want. Right now... it's
mostly just boring HTML tags... except for a number of these "blocks".

Blocks are basically "holes" into which a child template can place content. Let
me explain that in a different way. When we say `extends 'base.html.twig'`, that
basically says:

> Yo Twig! When you render this template, I want you to *actually* render
> `base.html.twig` ... and then put *my* content *inside* of it.

Twig then politely replies:

> Ok cool... I can do that. But *where* in `base.html.twig` do you want me to put
> all of your content? Do you want me to put it at the bottom of the page? At the
> top? Some random place in the middle?

The way we tell Twig *where* to put our content within `base.html.twig` is by
override a block. Notice that `base.html.twig` already has a block called `body`...
and that's *right* where we want to put our template's HTML.

To put it there, in our template, surround all of the content with
`{% block body %}`... and then `{% endblock %}`.

[[[ code('01d2fbf6f5') ]]]

This is called template inheritance because we are *overriding* that `body` block
with this new content. So now, when Twig renders `base.html.twig`... and it gets to
this `block body` part, it's going to print the `block body` HTML from *our* template

Watch: refresh and... the error is gone. And if you view the page source, we have a
full HTML page!

## Block Names

Oh, and the *names* of these blocks are *not* important. If you want to rename them
after your favorite 90's sitcom character, do it. Just remember to *also* update
its name in any child templates.

You can also add *more* blocks. Every block you add is just another potential
override point.

## Default Block Content

Oh, and you may have noticed that blocks *can* have default content. Look
at the page right now: the title says "Welcome". That's because the `title` block
has default content... and we're not overriding it. Let's change the default title
to "Mixed Vinyl".

[[[ code('086e2afb43') ]]]

So now *that* will be the title of every page on our site... *unless* we override
that. In our template, either above block body or below - the order of blocks
doesn't matter - add `{% block title %}`, `{% endblock %}` and, in between
"Create a new Record".

[[[ code('4ebc00a77a') ]]]

And now... yes! *This* page has a custom title.

## Adding to (Instead of Replacing) the Parent Block

Oh, and you might be wondering:

> What if I don't want to *replace* a block entirely.... but instead, I want to
> *add* to a block?

That's totally possible. In `base.html.twig`, the `title` block is set to "Mixed
Vinyl". If we wanted to *prepend* our custom title to that, we could say "Create
a new Record" then use the "say something" tag to print a function called `parent()`.

[[[ code('9729cce31f') ]]]

That does exactly what you'd expect: it finds the parent template's content for
this block.. and prints it. Refresh and... that's *so* nice.

## Template Inheritance is Class Inheritance

If you're ever confused about how template inheritance works, it's useful, for me
at least, to think about it *exactly* like object-oriented inheritance. Each
template is like a class and each block is like a method. So the homepage "class"
extends the `base.html.twig` "class", but overrides two of its methods. If that
only confused you, don't worry about it.

So... that's it for Twig. You're basically a Twig expert, which I'm told is a
popular topic at parties.

Next: one of the *killer* features of Symfony is its debugging tools. Let's get
these installed and check 'em out.

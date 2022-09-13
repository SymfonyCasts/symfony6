# Querying for a Single Entity for a "Show" Page

Our users *really* need to be able to click on a mix and navigate to a page with
more information about it... like eventually its track list! So let's make that
possible! Let's create a page to display just *one* mix's details.

## Creating the new Route & Controller

Head over to `src/Controller/MixController.php`. After the `new` action, add
`public function show()` with the `[#Route()]` attribute above. The URL for this
will be... how about `/mix/{id}`, where `id` will be the ID of that mix in the
database. Below, add the corresponding `$id` argument. And... just to see if this
is working, `dd($id)`.

[[[ code('32d49a76d0') ]]]

Coolio! Spin over and go to, how about, `/mix/7`. Awesome! Our route and controller
are hooked up!

## Querying for a Single Object

Ok, now that we have the ID, we need to query for the *one* `VinylMix` in the
database matching that. And we know how to query: via the *repository*. Add a
second argument to the method type-hinted with `VinylMixRepository` and call it
`$mixRepository`. Now replace the `dd()` with `$mix = $mixRepository->` and, for
the first time, we're going to use the `find()` method. It's dead simple:
it finds a single object using the primary key. So pass it `$id`. To make sure *this*
is working, `dd($mix)`.

[[[ code('859cc66b54') ]]]

We don't know which IDs we actually *have* in our database right now, so as a
workaround, go to `/mix/new` to create a *new* mix. In my case, it has ID 16.
Cool: go to `/mix/16` and... hello `VinylMix` `id: 16`! The important thing to
notice is that this returns a `VinylMix` *object*. Unless you do something custom,
Doctrine *always* gives us back either a *single* object or an *array* of objects,
depending on which method you call.

## Rendering the Template

Now that we have the `VinylMix` object, let's render a template and pass that in.
Do that with `return $this->render()` and call the template `mix/show.html.twig`.
The template path *could* be anything, but since we're inside `MixController`,
the directory `mix` makes sense. And since we're in the `show` action,
`show.html.twig` *also* makes sense. Consistency is a great way to make friends
with your fellow teammates!

Pass in a variable called `mix` set to the `VinylMix` object `$mix`.

[[[ code('a2f566cccc') ]]]

All right, let's go create that template. In `templates/`, add a new directory
called `mix/`... and inside of *that*, a new file called `show.html.twig`. Pretty
much every template is going to start the same way. Begin by saying
`{% extends 'base.html.twig' %}`.

[[[ code('8faba2464f') ]]]

As a reminder, `base.html.twig` has several blocks in it. The most important one
down here is `block body`. *That's* what we'll override with our content. At the top,
there's also a `block title`, which allows us to control the title of the page. Let's
override *both*.

Say `{% block title %}{% endblock %}` and, in between, `{{ mix.title }} Mix`.
Then override `{% block body %}` with `{% endblock %}` below. Inside, just
to get started, add an `<h1>` with `{{ mix.title }}`.

[[[ code('f7e4f8ec72') ]]]

When we try that... hello page! This is *super* simple - the `<h1>` isn't even in
the right place - but it's *working*. Now we can add some *pizzazz*.

## Making the Page All Fancy Looking

I'm going to head back to my template and paste in a bunch of new content. You can
copy this from the code block on this page. The top of this is *exactly* the same:
it extends `base.html.twig` and the `block title` looks like it did before. But
then, in the body, we have a bunch of new markup, we print the mix title... and
down here, I have a few `TODO`s for us where we'll print out more details.

[[[ code('29a9b90ee9') ]]]

If you refresh now... nice! We even have the cute little record SVG... which you
probably recognize from the homepage. That's awesome... except that duplicating
this entire SVG in both templates is... *not* so awesome. Let's fix that duplication.

## Avoiding Duplication with a Template Partial

Select all of this `<svg>` content, copy it, and over in the `mix/` directory, create
a new file called `_recordSvg.html.twig`. Paste that here!

[[[ code('25e389c19c') ]]]

The reason I prefixed the name with `_` is to indicate that this is a template
*partial*. That means it's a template that doesn't include a whole page - just *part*
of a page. The `_` is optional... and just something that's done as a common
convention: it doesn't change any behavior.

Thanks to this, we can go into `show.html.twig` and
`{{ include('mix/_recordSvg.html.twig) }}`. 

[[[ code('d32f13df1d') ]]]

Let's go do the same thing in the homepage
template: `templates/vinyl/homepage.html.twig`. This is the *same* SVG here, so
we'll include that same template.

[[[ code('1887b55a99') ]]]

Nice! If we go check the homepage... it *still* looks great! And if we head back to
the mix page and refresh... that looks great too!

To finish the template, let's fill in the missing details. Add an `<h2>` with
`class="mb-4"`, and inside, say `{{ mix.trackCount }} songs`, followed by a `<small>`
tag with `(genre: {{ mix.genre }})`... and below this, a `<p>` tag with
`{{ mix.description }}`.

[[[ code('ec3ff968e2') ]]]

And now... this is starting to come to life! We don't have a track list yet...
because that's another database table we'll create in a future tutorial. But it's
a nice start.

## Linking to the Show Page

To complete the new feature, when we're on the `/browse` page, we need to *link*
each mix to its show page. Open `templates/vinyl/browse.html.twig` and scroll down to
where we loop. Ok: change the `<div>` that surrounds everything to an `<a>` tag.
Then... break this onto multiple lines and add `href=""`. As you can see, PhpStorm
was clever enough to update the closing tag to an `a` *automatically*.

To link to a page in Twig, we use the `path()` function and pass the name of the
route. What... *is* the name of the route to our show page? The answer is...
it doesn't *have* one! Ok, Symfony auto-generates a name... but we don't want to
rely on that. As soon as we want to link to a route, we should give that route a
proper name. How about `app_mix_show`.

[[[ code('46272d3c62') ]]]

Copy that, head back to `browse.html.twig` and *paste*.

But this time, just pasting the route name isn't going to be enough! Check out
this sweet error:

> Some mandatory parameters are missing ("id") to generate a URL for route
> "app_mix_show".

That makes sense! Symfony is trying to generate the URL to this route,
but we need to tell it what *wildcard* value to use for `{id}`. We do that by
passing a second array argument with `{}`. Inside set `id` to `mix.id`.

[[[ code('4ef34eaf67') ]]]

And now... the page works! And we can click any of these to hop in and
see more details.

Okay, we've got the happy path working! But what if *no* mix can be found for a given
ID? Next: let's talk 404 pages *and* learn how we can be even *lazier* by getting
Symfony to query for the `VinylMix` object *for* us.

# Show Page

If a user on our site likes one of the mixes, it would be cool if they could click on it and navigate to a page with more information about it. Let's do it! Let's create a page to display just one mix's detail. Head over to `src/Controller/MixController.php`. We already have our `new` action down here. Let's add a new `public function show()` and then add the `#Route['']` above. The URL for this will be... how about `/mix/{id}`, where `id` will be the ID of that mix in the database. Down here, I'll add a corresponding `$id` argument. And, just to see if this is working, let's `dd($id)`. Cool! Spin over and let's go to `/mix/7`. Awesome! Our route and controller are hooked up!

All right, now that we have this ID, what we need to do is query for the vinyl mix in the database *for* that ID. And we know how to query - via the *repository*. Let's add a second argument here, type hinted with `VinylMixRepository`, and let's call it `$mixRepository`. Then, we're going to replace this `dd` with `$mix = $mixRepository->`, and for the first time, we're going to use the `find()` method. This method is set up to find something by its primary key, so let's pass it `$id`. And to make sure *this* is working, `dd($mix)`.

We don't know which IDs we have in our database right now, so a temporary solution is to go to `/mix/new`, which creates a *new* mix with, in my case, ID 16. I'll put "16" in the URL and... hello `VinylMix` `id: 16`! Notice that this returns a `VinylMix` object. Doctrine always gives us objects back by default.

Now that we have the mix object, all we need to do is render a template, pass that into the template, and start printing out data. Say `return $this->render()`, and let's call the template `mix/show.html.twig`. Once again, this is `MixController.php`, so I'm calling the directory `mix`. This is it's `show` action, so I used `show.html.twig`. That naming convention is totally optional, but you'll make lots of friends if you keep things nice and predictable in your code. Let's pass in a variable called `mix` set to our `VinylMix` object, `$mix`.

All right, let's go create that template. In `/templates`, create a new directory called `/mix`. Inside of *that*, create a new file called `show.html.twig`. Pretty much every template is going to start the same way. We'll begin by saying `{% extends 'base.html.twig' %}`. As a reminder, `base.html.twig` has several blocks in it, and the most important `block` down here is `block body`. That's what we'll override with our content. At the top, there's also a `block title`, which allows us to control the title of the page. Let's override *both* of those.

I'll say `{% block title %}{% endblock %}`. In between, say `{{ mix.title }} Mix`. Let's also override `{% block body %}` here and `{% endblock %}` below. Inside, just to get started, I'll add a `<h1>` with `{{ mix.title }}`. When we try that... hello page! This is *super* simple. The `<h1>` isn't even in the right place, but it's *working*. Now we can add a little *pizzazz*.

I'm going to head back to my template and paste in a bunch of new contents. You can copy all of this from the code block on this page. The top of this is *exactly* the same. It extends the same `base.html.twig` and the `block title` is the same. But then, it pulls in a bunch of markup and prints the mix title. Down here, I have a couple of `TODO`s for us, where we'll print out more details. If you refresh this... nice! We now have a cute little record SVG here, which you probably recognize from the homepage. Awesome! The only *not-awesome* thing is now we have this SVG duplicated on *both* of those pages. While we're here, let's clean that up.

I'm going to select all of this `<svg>` content, copy it, and over in the `/mix` directory, create a new file called `_recordSvg.html.twig`. Paste that in here and... beautiful! The reason I put an `_` at the beginning of this template is to indicate that it's a template *partial*. That means it's a template that doesn't include a whole page - just *part* of a page. The `_` is totally optional and just something that's commonly done as convention, but it doesn't actually change any behavior.

Thanks to this, we can go into our `show.html.twig` template and `{{ include('mix/_recordSvg.html.twig) }}`. Let's go do the same thing in the homepage template: `/templates/vinyl/homepage.html.twig`. This is the *same* SVG here, so we'll include that same template. Nice! If we go check the homepage... it *still* looks great! And if I head back to the `/mix` page and refresh... that looks great too!

The last thing we need to do to get our show page working is fill in some of the details here, so let's print out a few things! Add a `<h2>` with `class="mb-4"`, and inside, say `{{ mix.trackCount }} songs`, followed by a `<small>` tag where we'll say `(genre: {{ mix.genre }})`. Then, below this, I'll add a `<p>` tag and say `{{ mix.description }}`. And now... awesome! This is really starting to come to life! We don't have a track list here yet because that's another database table we'll need to create in a future tutorial, but we have a really nice start.

All right, the last obvious thing to do here is *link*, meaning when we're on the `/browse` page, we can click certain items to get to our information page. Open `/templates/vinyl/browse.html.twig` and scroll down to where we loop. Here's what we're going to do: Let's change the `<div>` that surrounds everything to an `<a>` tag. Then, let's break this onto multiple lines and add `href=""`. As you can see, PhpStorm is smart enough to update the closing tag *automatically*.

When we link to something from Twig, we use the `path()` function, and then we pass the name of the route right here. So what's the name of the route to our show page? The answer is... it doesn't *have* a name. In reality, Symfony is auto-generating one, but we don't want to rely on that. As soon as we want to link to a route, we want to give it a proper name, so how about `app_mix_show`. Copy that, head back to `browse.html.twig`, and *paste*.

The only thing that's unique in this case is that just *pasting* the route name isn't going to be enough. We're going to get a giant error:

`An exception has been thrown during the rendering
of a template ("Some mandatory parameters are
missing ("id") to generate a URL for route
"app_mix_show".")`

That makes *perfect* sense. It's trying to generate the URL to this route, but we need to pass it the wildcard to fill in for ID. We do that by passing a second argument with `{}` with `id: mix.id`. And now... the page works! Awesome! And we can click any of these to hop to that particular page.

Okay, we've got the path working, but what if *no* mix can be found for a given ID? We need to talk about the 404 page and a way that we can query for this `VinylMix` object without doing any work from our controller. That's *next*.

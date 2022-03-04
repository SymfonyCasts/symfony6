# Assets, CSS, Images, etc

If you download the course code from this page where you're watching this video,
after unzipping, you'll find a `start/` directory that contains these same brand
new Symfony 6 app that we created earlier. You don't actually need this code, but
it it *does* contain one extra directory called `tutorial/`. Like I have here. This
holds some files that we are about to use.

So let's talk about our next goal: to make this site look like a *real* site...
instead of looking like something I designed myself. And *that* means that we need
a true HTML layout that brings in some CSS.

## Adding a Layout & CSS Files

We know that our layout file is `base.html.twig`... and there's *also* a
`base.html.twig` file in the new `tutorial/` directory. Copy that... paste it
in templates, and override the original.

Before we look at that, *also* copy the three `.png` files and put those into
the `public/` directory so that our users can access them.

Beautiful. Open up the new `base.html.twig` file. There's nothing special here. We
bring in some *external* CSS files from some CDNs for Bootstrap and FontAwesome.
By the end of this tutorial, we'll refactor this into a *fancier* way of handling
CSS. But for right now, this will work great.

But otherwise, everything is still hardcoded. We have some hardcoded navigation,
we still have the same block body... and a hardcoded footer. Let's go see what it
looks like. Refresh and woo! Well, not perfect, but better.

## Adding a Custom CSS File

The `tutorial/` directory also holds `app.css` file with some custom CSS. To make
this publicly available so that our user's browser can download it, it needs to live
*somewhere* in the `public/` directory. But it doesn't matter where or how you
organize things inside.

Let's create a `styles/` directory... and then copy `app.css`... and paste it
there.

Back in, `base.html.twig`, head back to the top. After I bring in all the external
CSS files, let's add a link tag for *our* `app.css`. So `<link rel="stylesheet"`
and `href=""`. Because the `public/` directory is our document root, to refer to
a CSS or image file there, the path will be with respect to *that* directory.
So this will be `/styles/app.css`.

Let's check it. Refresh now and... even better!

## The asset() Function

Now, I want you to notice something. So far, Symfony is *not* involved at *all* in
how we organize or use images or CSS files. Nope. Our setup is dead simple: we
put things in the `public/` directory... then refer to them with their paths.

But *does* Symfony have any cool features to help work with CSS and JavaScript?
Absolutely. It's called Webpack Encore and stimulus. And we'll talk about both of
those towards the end of the tutorial.

But even in this simple setup - where we just put files in `public/` and point to
them, there is one *minor* feature: the `asset()` function.

It works like this: instead of using `/styles/app.css`, `{{ asset() }}` and then,
inside quotes, move our path there... but *without* the opening "/".

So the path is still relative to the `public/` directory... you just don't need
to include the opening "/".

Before we talk about what this does... let's go see if it works. Refresh and...
it doesn't! Error:

> Unknown function: did you forget to run `composer require symfony/asset`.

I keep saying that Symfony starts small... and then you install things *as* you need
them. Apparently, this `asset()` function comes from a part of Symfony that we don't
have yet! But getting it is easy. Copy this comeposer require command, spin over
to your terminal and run it:

```terminal-silent
composer require symfony/asset
```

This is a pretty simple install: it downloaded just this one package... and there
were no recipes.

But when we try the page now... it works! Check out the HTML source for this page.
hmm. The `link` tag's `href` is still, literally `/styles/app.css`: that's *exactly*
what we had before! So what the heck is this `asset()` function doing?

The answer is... not much. But it's still a good idea to use. The `asset()` function
gives you two features. First, imagine you deploy to a sub-directory or a domain.
Like, our homepage lives at example.com/mixed-vinyl.

If that were the case, then in order for our CSS to work, the `href` would need
to be `/mixed-vinyl/styles/app.css`. *If* you have the situation, the `asset()`
function will detect the sub-directory automatically and add that prefix for you.

The second - and more important thing that the `asset()` function does - is allow
you to easily switch to a CDN later. Because this path is now going through the
`asset()` function, we could, via a configuration file, say:

> Hey Symfony! When you output this path here, please prefix it with the URK
> to my CDN.

This means that, when we load the the page, instead of `href="/styles/app.css`, it
would say be something like `https://mycdn.com/styles/app.css`.

So the `asset()` function might not be doing anything you need *today*, but anytime
you reference a static file - whether it's a CSS file, JavaScript file, image, whatever,
use this function.

For example up here, I'm referencing these three images. Let's use the asset:
`{{ asset()`... and then it auto-completes the paths! thanks Symfony plugin! Repeat
this for the second image... and the third.

We know this won't make any difference today... we can refresh the HTML source page...
but we're ready for a CDN in the future.

## Homepage And Browse Page HTML

So the layout now looks great! But the content for our homepage is... just kind of
kind hanging out... looking weird, like me in middle school. Back in the `tutorial/`
directory, copy the homepage template... and overwrite our original file.

Open that up. This still extends `base.html.twig`... and it still overrides the
`body` block. And then, has a bunch of completely hard coded HTML. Lets' go see
what that looks like. Refresh and... that looks awesome!

The only problem is that it's 100% hard coded. Let's fix that. All the way on top,
Here's the name of our record, print the `title` variable.

And then down here for the song list.. we have a long list of hardcoded HTML.
Let's turn this into a loop. Add `{% for track in tracks %}` like we had before.
And... at the end, `endfor`.

For the song details, use `track.song`... and `track.artist`. And now we can
remove *all* other other hardcoded songs.

Sweet! Let's try that. And now... it's coming to life!

One more page to go: our `/browse` page. You know the drill: copy `browse.html.twig`,
and overwrite the original. This looks a lot like the homepage, extending
`base.html.twig` and overriding block `body`.

Over in `VinylController`, we weren't rendering a template yet... so let's do that
now: `return $this->render('vinyl/browse.html.twig')` and pass in the genre. Add
a variable for that: `$genre =` and if we have a slug... use our fancy title
title case code, else set this to null. Then delete the `$title` stuff. Pass
`genre` into Twig.

Back in the template, use this in the `h1`. In Twig, we can *also* use fancy
syntax. So *if* we have a `genre`, print `genre`, else print `All Genres`.

Testing time. Head over to `/browse`: "Browse all genres" And then
`/browse/death-metal`: Browse Death Metal. Friends, this is starting to feel like
a real site!

Except that these links up in the nav... go absolutely nowhere! Let's fix that
next by learning how to generate URLs next. We're also going to meet the mega powerful
`bin/console` command line tool.

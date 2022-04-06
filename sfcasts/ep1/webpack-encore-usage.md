# Packaging JS and CSS with Encore

When we installed Webpack Encore, its recipe gave us this new `assets/` directory.
Check out the `app.js` file. Interesting. Notice how it *imports* this `bootstrap`
file. That's actually `bootstrap.js`: this file right here. The `.js` extension
is optional.

## JavaScript Imports

This is one of the most *important* things that Webpack gives us: the ability to
*import* one JavaScript file from another. We can import functions, objects...
really *anything* from another file. We're going to talk more about this
`bootstrap.js` file in a little bit.

This also imports a CSS file!? If you haven't seen this before, it might look...
weird: JavaScript importing CSS?

To see how this all works, in `app.js`, add a `console.log()`.

[[[ code('37b4bd7997') ]]]

And `app.css` already has a body background... but add an `!important` so that 
we can *definitely* see if this is being loaded.

[[[ code('28ba4bdd4d') ]]]

Ok... so who *reads* these files? Because... they don't live in the `public/`
directory... so we can't create `script` or `link` tags that point directly to them.

## webpack.config.js

To answer that, open `webpack.config.js`. Webpack Encore is an executable binary:
we're going to run it in a minute. When we *do*, it will load this file to get
its config.

And while there are a *lot* of features inside of Webpack, the only thing we need
to focus on right now is this one: `addEntry()`. This `app` could be anything...
like `dinosaur`, it doesn't matter. I'll show you how that's used in a minute. The
important thing is that it points to the `assets/app.js` file. Because of this,
`app.js` will be the first and *only* file that Webpack will parse.

It's pretty cool: Webpack will reads the `app.js` file and then follow *all* of the
`import` statements *recursively* until it finally has a giant collection of *all*
the JavaScript and CSS our app needs. Then, it will *write* that into the `public/`
directory.

## Running Webpack Encore

Let's see it in action. Find your terminal and run:

```terminal
yarn watch
```

This is, as it says, a shortcut for running `encore dev --watch`. If you look
at your `package.json` file, it came with a `script` section with some
shortcuts.

Anyways, `yarn watch` does two things. First, it created a new `public/build/`
directory and, inside, `app.css` and `app.js` files! But don't let the names
fool you: `app.js` contains a lot more that *just* what's inside `assets/app.js`:
it contains *all* the JavaScript from *all* the imports it finds. `app.css`
contains all the *CSS* from all the imports.

The reason these files are called `app.css` and `app.js` is because of the entry
name.

So the takeaway is that, thanks to Encore, we suddenly have new files in a
`public/build/` directory that contain *all* the JavaScript and CSS our app needs!

## The Encore Twig Functions

And if you move over to your homepage and refresh... woh! It instantly worked!? The
background changed... and in my inspector... there's the console log! How the heck
did that happen?

Open your base layout: `templates/base.html.twig`. The secret is in the
`encore_entry_link_tags()` and `encore_entry_script_tags()` functions. I bet you
can guess what these do: add the `link` tag to `build/app.css` and the `script`
tag to `build/app.js`.

You can see this in your browser. View the source for the page and... yup! The link
tag for `/build/app.css`... and `script` tag for `/build/app.js`. Oh, but it also
rendered two *other* `script` tags. That's because Webpack is really smart. For
performance purposes, instead of dumping one *gigantic* `app.js` file, sometimes
Webpack will *split* it into multiple, smaller files. Fortunately, these Encore
Twig functions are smart enough to handle that: it will include *all* the link
or script tags needed.

The *most* important thing is that the code that we have in our `assets/app.js`
file - including anything it imports - is now functioning and showing up on our
page!

## Watching for Changes

Oh, and because we ran `yarn watch`, Encore is still running in the background
watching for changes. Check it out: go into `app.css`...  and change the background
color. Save, move over and refresh. 

[[[ code('9947a18198') ]]]

It instantly updated! That's because Encore *noticed* the change and recompiled 
the built file really quickly.

Next: let's move our *existing* CSS into the new system and learn how we can
install and import *third party* libraries - look Bootstrap or FontAwesome - right
into our Encore setup.

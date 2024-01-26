# Encore -> AssetMapper Part 2

Getting 3rd party CSS files working is one of the *trickier* things to do in
AssetMapper. Importing them like this isn't going to work.

## Installing Bootstrap CSS

Let's focus on Bootstrap first. This is a third-party package and we install
third-party packages by saying `bin/console importmap:require` the package name:

```terminal-silent
php bin/console importmap:require bootstrap
```

Bootstrap is especially interesting because it grabs the JavaScript package, a
*dependency* of the JavaScript package and it *also* noticed that this package
commonly has a CSS file, so it grabbed that *too*. All three of these things were
added to `importmap.php`.

In our project, we're not using the bootstrap JavaScript at all. So we *could* delete
this, but I'll leave it because it's not hurting anything. The real star, however,
is this CSS file. Copy this path. And an `app.css`, remove the top line.

You *can* import third party CSS with AssetMapper, but *can't* do it from inside
another CSS file. Well, you technically can, but life is easier if we do it from
`app.js`. Say `import`, then paste.

And now... Bootstrap works

## Adding FontAwesome

Next up is FontAwesome. Notice that we're grabbing a *specific* CSS file from the
package. One big difference between Encore and AssetMapper is that if you need to
import a specific file from a package, you need to `importmap:require` *that* file,
not just the package in general. Watch `bin/console importmap:require` and
paste:

```terminal-silent
php bin/console importmap:require @fortawesome/fontawesome-free/css/all.css
```

That grabs this *one* CSS file, downloads it into the project and adds it to
`importmap.php` right here. If you're curious, these files are downloaded into
an `assets/vendor/` directory.

Head into `app.css`, remove that line and add another import for that path.

And *that* works! Though, on the topic of FontAwesome, I don't recommend using
FontAwesome like this anymore. Instead, I recommend using FontAwesome kits.
*Or*, better, render an inline SVG. Hopefully we'll have an icon package soon
from Symfony UX to make that easier.

## Adding CSS Fonts

The last item in `app.css` is a font. This is trickier. If we run `importmap:reqiure`
followed by *only* a package name - no path - it will always download the package's
JavaScript file. You only get a CSS file if you `importmap:require` a path *to*
a CSS file, like we just did.

Ok, I know earlier we ran `import:require bootstrap` and that *did* give us a CSS
file. So, let me be more clear. If you run `importmap:require packageName`, you'll
get the *JavaScript* for that package. In *some* cases, like Bootstrap, the package
advertises that it has a CSS file. When that happens. AssetMapper sees that and,
effectively, runs `importmap:require bootstrap/dist/css/bootstrap.min.css`
automatically.

Anyway, I know we need a CSS file. With Encore, if you imported a package *from* a
CSS file, Encore would try to find the CSS file in this package and import that.
That won't happen with AssetMapper: we need to figure out *what* the path is to the
CSS file then require that.

I like to do this at jsDelivr.com. This is the CDN that AssetMapper uses behind
the scenes to fetch its packages. Search for the package. It shows up, but there's
one below from `@fontsource-variable`. Variable fonts can bebit more efficient,
so let's change to that use this. Inside, hey! It advertises the main CSS file
If you wanted a different file, you could click the Files tab and navigate to find
what you want.

Copy this path all the way down to the package name, then spin over and run
`importmap:require` and paste. But we don't need the version in the name: just
the package, then the path:

```terminal-silent
php bin/console importmap:require @fontsource-variable/roboto-condensed/index.min.css
```

Copy that and hit enter. It downloads the CSS file and adds an entry to
`ipmortmap.php`. Finally, remove the import from `app.css` and import it from
`app.js`.

Oh, and because we changed to the variable font, in `app.css`, update the font family
to `Roboto Condensed Variable`.

Over on the site, watch the font when I refresh. Got it! Grabbing those third
party CSS files might be the *trickiest* thing you'll do in AssetMapper.


Oh, and if you're using Sass or Tailwind, there are Symfonycasts bundles to 
support both of those in AssetMapper.

## Adding the .js Extension

Now that styling is working, let's look into our JavaScript. In the console, we
have an error: a 404 for something called `bootstrap`. That's coming from
`app.js`: from this import line. To fix that, open `app.js` and add `.js` to the
end.

When working with Webpack Encore, you're inside of a Node environment. And Node
lets you cheat. If the file you're importing ends in `.js`, you don't need to
include `.js`. But in a *real* JavaScript environment, like your browser, you
*can't* do that: the `.js` *is* needed.

This is probably the number one change you'll need to make when converting. You
might need to add the `.js` onto the end of a *bunch* of lines, but it's easy.

## stimulus-bridge -> stimulus-bundle

Try the page now. Next error! And it's important:

> Failed to resolve module specifier `@symfony/stimulus-bridge`.

This means that, somewhere, we're importing this package... but that package doesn't
exist in `importmap.php`.

There are two types of imports. First, if an import starts with `./` or `../`,
that's a relative import. Those are simple: you're importing a file next to this
file. The second type is called a *bare* import. This is when you're importing a
package or a file in a package. For these, the string inside the import must *exactly*
exist in `importmap.php`. If it doesn't, you'll see this error.

The source of our error is `bootstrap.js`. See this `@symfony/stimulus-bridge`?
That does *not* exist in `importmap.php`. The solution, usually, is to install
this!

But in this case, the package is specific to Webpack Encore and the fix is related
to our migration. Change this to `@symfony/stimulus-bundle`.

And lo and behold: that string *does* live inside `importmap.php`! Below, the
next line simplifies.

But it does the same as before: starts the Stimulus app and load our controllers.
If you start a new Symfony app, you get all this with the recipe. But since we're
converting, we need to do a bit more work by hand.

## Installing Missing Packages

When we refresh now, we get the exact same error but with a different package
called `axios`. You know the drill: somewhere, we're importing this... but it
doesn't live in `importmap.php`. In this case, it's coming from
`song-controls_controller.js`.

And *this* time, the fix *is* to install this package! Spin over and run
```terminal
php bin/console  importmap:require axios
```

That adds `axios` to `importmap.php` and now... our app is alive! This is
powered by AssetMapper, performant and all with no build system.

## Downgrading a Dependency

Oh, but look at the footer: the text is darker than it used to be. That's because,
I was previously using bootstrap 5.1... but when we installed that with AssetMapper,
it grabbed the latest 5.3. And apparently something changed.

I could figure out what changed and fix this. But we can also downgrade: change
the version in `importmap.php` to 5.1.3.

If we just change that and refresh, nothing will change: the newer version is *still*
downloaded into `assets/vendor`. To sync that directory with `importmap.php`, run:

```terminal
php bin/console importmap:install
```

This is the `composer install` for AssetMapper. It noticed that we changed two
packages and downloaded those. And now... we're good! We're done! We're running
AssetMapper!

Next up, let's take two minutes to modernize & simplify some JavaScript.

# Installing 3rd Party Code into our JS/CSS

We now have a nice new JavaScript and CSS system that lives entirely inside of the
`assets/` directory. Let's move our public styles into this. Open
`public/styles/app.css`, copy all of this, delete the *entire* directory... and
then paste into the new `app.css`. Thanks to the `encore_entry_link_tags()` in
`base.html.twig`, the new CSS *is* being included... and we don't need the old
`link` tag anymore.

Go check it out. Refresh and... it still looks great!

## Installing 3rd Party JavaScript/CSS Libraries

Go back to `base.html.twig`. What about these external link tags for bootstrap
and FontAwesome? Well, you can *totally* keeps these CDN links. But we can
*also* process this stuff through Encore. How? By installing Bootstrap and
FontAwesome as *vendor* libraries and *importing* them.

Remove *all* of these link tags... and then refresh. Yikes! It's back to looking
like *I* designed this site. Let's... *first* re-add bootstrap. Find your terminal.
Since the watch command is running, open a new terminal tab and then run:

```terminal
yarn add bootstrap --dev
```

This does three things. First, it adds `bootstrap` to our `package.json` file. Second
it downloads bootstrap into our `node_modules/` directory... you would find it down
here. And *third*, it updated the `yarn.lock` file with the exact version of bootstrap
that it just downloaded.

If we stopped now... this wouldn't make any difference! We downloaded bootstrap -
yay - but we're not using it.

To use it, we need to *import* it. Go into `app.css`. Just like in JavaScript files,
we can import from inside *CSS* files by saying `@import` and then the file. We could
reference a file in the same directory with `./other-file.css`. *Or*, if you want
to import something from the `node_modules/` directory in CSS, there's a trick:
a `~` and then the package name: `bootstrap`.

[[[ code('43abc8b3a6') ]]]

That's it! As *soon* as we did that, Encore's watch function rebuilt our `app.css`
file... which now *includes* Bootstrap! Watch: refresh the page and... we're back!
So cool!

The two *other* things we're missing are `FontAwesome` and a specific Font. To add
those, head back to the terminal and run:

```terminal
yarn add @fontsource/roboto-condensed --dev
```

Full disclosure: I did some searching *before* recording so that I knew the names
of all the packages we need. You can search for packages at https://npmjs.com.

Let's also add the last one we need:

```terminal
yarn add @fortawesome/fontawesome-free --dev
```

Again, this downloaded the two libraries into our project... but doesn't automatically
*use* them yet. Because those libraries both hold CSS files, go back to our
`app.css` file and import them: `@import '~'` then `@fortawesome/fontawesome-free`.
And `@import '~@fontsource/roboto-condensed'`.

[[[ code('c72cdebd5f') ]]]

The first package should fix this icon... and the second should cause the font to
change on the whole page. Watch the font when we refresh... it *did* change! But,
uh... the icons are still kind of broken.

## Importing Specific Files from node_modules/

To be totally honest, I'm not sure why that doesn't work out-of-the box. But the
fix is kind of interesting. Hold command on a Mac - or ctrl otherwise - and
click this `fontawesome-free` string.

When you use this syntax, it goes into your `node_modules/` directory, into
`@fortawesome/fontawesome-free`... and then if you don't put any filename after this,
there's a mechanism where this library tells Webpack *which* CSS file it should
import. By default, it imports this `fontawesome.css` file. For some reason... that
doesn't work. What we want is this `all.css`.

And we can import *that* by adding the path: `/css/all.css`. We don't need the
minified file because Encore handles minifying for us.

[[[ code('0e00a5819a') ]]]

And now... we're back!

The *main* reason I love Webpack Encore and this system is that it allows us to
use proper imports. We can even organize *our* JavaScript into small files - putting
classes or functions into each - and then import them when we need them. There's no
more need for global variables.

Webpack also allows us to use more serious stuff like React or Vue: you can even
see, in `webpack.config.js`, the methods to activate those.

But usually, I like using a delightful JavaScript library called Stimulus. And I
want to tell you about it next.

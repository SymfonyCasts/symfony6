# Migrating Encore -> AssetMapper

Symfony 6.3 came with a new component called AssetMapper... and I love
it! Okay, I work on it... so I'm totally *not* objective... but trust me it's amazing!
It lets us write modern JavaScript and css with *no* build system. We have an
[Asset Mapper tutorial](https://symfonycasts.com/screencast/asset-mapper)
and a more recent [LAST Stack tutorial](https://symfonycasts.com/screencast/last-stack)
where we build cool stuff with it.

## AssetMapper Vs Webpack Encore?

AssetMapper is a replacement for Webpack Encore. Encore isn't going to die super
soon, but I definitely caught it browsing some retirement brochures!

So I know what you're wondering:

> Should I convert my app from Webpack Encore to AssetMapper?

The short, but not satisfying answer is... it's up to you. AssetMapper is more modern,
it's easier to use and if you're frustrated with slow builds from Encore,
that's a great reason to switch. But if Encore is working fine,
there's no *huge* reason to do all the work of converting to AssetMapper.
Also, if you use React or Vue, you'll want to stay with Encore because those *do*
still require a build step.

## Removing Webpack Encore

But let's convert! Head over to your terminal and find that tab where `yarn watch`
is doing its thing. Stop that with Ctrl+C and close that tab. We do *not* need a
build system - so that second tab is *not* coming back.

Then run:

```terminal
composer remove symfony/webpack-encore-bundle
```

This will remove that package... but more important: its recipe will *uninstall*
itself! It feels great: `package.json` gone, `webpack.config.js` gone, the
`encore_entry_` functions in `base.html.twig` gone.

But... it also deleted `app.js` and `app.css`. We *do* want those files, so run

```terminal
git checkout assets/
```

to get them back. But everything else looks good! Run:

```terminal
git diff
```

In the old `package.json`, the dependencies here were related to Webpack Encore
and we will *not* need those. But some of these are for our frontend, and we
*will* re-add those via AssetMapper.

Ok, lock in those changes with a commit... then throw a party by removing
`node_modules`, `public/build/` and the yarn error file. Oh, we can also remove
`yarn.lock`. Gorgeous!

## Installing AssetMapper

*Now* let's install AssetMapper:

```terminal
composer require symfony/asset-mapper
```

Its recipe does a bunch of interesting things. We won't go too deep into how
AssetMapper works - we have other tutorials for that - but let's explore.
In `.gitignore`:

[[[ code('93c93b545b') ]]]

it ignores the final location of the built assets and where
the vendor files live. And in `templates/base.html.twig`, it added an `importmap()`
function that will output CSS and JavaScript.

[[[ code('b0b941ddae') ]]]

It also gave us an `importmap.php` file. 

[[[ code('668b1573ff') ]]]

This is, effectively, the new `package.json`: the home for 3rd party packages.
And hey! It already added Stimulus and Turbo! Those are two of the packages from
`package.json` that we *do* need.

Will this work? Refresh and... kinda? We don't have Bootstrap CSS... which is why
it looks terrible. But I *can* see that `assets/styles/app.css` *is* being loaded:
that's giving us some basic styles. But we need to fix these imports.

[[[ code('0d828a4a27') ]]]

Onwards we go! Let's roll up our sleeves and nail down the last few steps to get
AssetMapper up and running next.

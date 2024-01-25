# Migrating Encore -> AssetMapper

Symfony 6.3 came with a new component called AssetMapper... and I absolutely love
it. Okay, I work on it so I'm totally *not* objective... but trust me it's amazing!
It allows us to write modern JavaScript and css with *no* build system. We have an
[Asset Mapper tutorial](https://symfonycasts.com/screencast/asset-mapper)
and a more recent [LAST Stack tutorial](https://symfonycasts.com/screencast/last-stack)
tutorial where we build cool stuff with it.

## AssetMapper Vs Webpack Encore?

AssetMapper is a replacement for Webpack Encore. Encore isn't going to die super
soon, but its days are numbered. So I know what you're wondering:

> Should I convert my app from Webpack Encore to AssetMapper?

The short, but not satisfying answer is... it's up to you. AssetMapper is more modern,
it's easier to use and if you're frustrated with slow builds from Webpack Encore,
that might be a really good reason to switch. But if Encore is working fine,
there's not a *huge* reason to do all the work of converting it into AssetMapper.
Also, if you use React or Vue, you'll want to stay with Encore because those *do*
still require a build step.

## Removing Webpack Encore

But let's convert! At your terminal, go to the first tab that's running `yarn watch`,
stop that with Ctrl+C and close that tab. We do *not* need a build system - so
that second tab is *not* coming back.

Then run:

```terminal
composer remove symfony/webpack-encore-bundle
```

This will remove that package... but even more important: its recipe will *uninstall*
itself and remove a bunch of stuff. It feels great: `package.json` gone,
`webpack.config.js` gone, the `encore_entry_` functions in `base.html.twig` gone.

But... it also deletes `app.js` and `app.css`. We *do* want those files, so run

```terminal
git checkout assets/
```

so we can get those back. But everything else looks good! Oh, but run:

```terminal
git diff
```

In the old `package.json`, the dependencies here were related to Webpack Encore
and we will *not* need those. But some of these are for our frontend, and we
*will* re-add those soon via AssetMapper.

Ok, commit this.... then celebrate by removing `node_modules`, `public/build/`
and the yarn error file. Oh, we also can remove `yarn.lock`. Gorgeous!

## Installing AssetMapper

*Now* let's install AssetMapper:

```terminal
composer require symfony/asset-mapper
```

Its recipe does a bunch of interesting things. We won't go too deep into how
AssetMapper works - we have other tutorials for that - but let's look at this.
In `.gitignore`, it ignores the final location of the built assets and where
the vendor files live. And in `templates/base.html.twig`, it added an `importmap()`
function, which will output CSS and JavaScript. It also gave us an `importmap.php`
file. This is, effectively, the new `package.json`: the home for 3rd party packages.
And hey! It already added Stimulus and Turbo! Those are two of the packages from
`package.json` that we *do* need.

Will this work? Refresh and... kinda? We don't have Bootstrap CSS... which is why
it looks terrible. But I *can* see that `assets/styles/app.css` *is* being loaded:
that's giving us some basic styles. But we need to fix these imports.

Let's do that next - and tackle the final few things needed to get AssetMapper
fully running.

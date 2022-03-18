# Setting up Webpack Encore

Our CSS setup is fine. We put files into the `public/` directory and then...
we point to them from inside our templates. We could add JavaScript files the
same way.

But if we want get truly serious about writing CSS and JavaScript, we need to take
this to the next level. And even if you consider yourself a mostly backend developer,
the tools we're about to talk about will allow you to write CSS and JavaScript that
feels easier and is less error-prone than what you're probably used to.

The key to taking our setup to the next level is leveraging a node library called
Webpack. Webpack is the industry standard tool for packaging, minifying and parsing
your frontend CSS, JavaScript, and other files. But don't worry: Node is just
JavaScript. And its role in our app will be pretty limited.

Setting up Webpack *can* be tricky. And so, in the Symfony world, we use a lightweight
tool called Webpack Encore. It's still Webpack... it just makes it easier! And we
have a free tutorial about it if you want to dive deeper.

## Installing Encore

But let's do a crash course right now. First, at your command line, make sure you
have Node installed:

```terminal
node -v
```

You'll also need either `npm` - which comes with Node automatically - or `yarn`:

```terminal-silent
yarn --version
```

Npm and yarn are Node package managers: they're the Composer for the Node world...
and you can use either. If you decide to use yarn - thats what I'll use - make sure
to install version 1.

We're about to install a new package... so let's commit everything:

```terminal
git add .
```

And... looks good:

```terminal-silent
git status
```

So commit everything:

```terminal-silent
git commit -m "Look mom! A real app"
```

To install Encore, run:

```terminal
composer require encore
```

This installs WebpackEncoreBundle. Remember, a bundle is a Symfony plugin.
And this package has a recipe: a very important recipe. Run:

```terminal
git status
```

## The Encore Recipe

Ooh! For the first time, the recipe modified the `.gitignore` file. Let's go check
that out. Open `.gitignore`. The stuff on top is what we originally had... and
down here is the new stuff added by WebpackEncoreBundle. It's ignoring the
`node_modules/` directory, which is basically the `vendor/` directory for Node.
We don't need to commit that because those vendor libraries are described in
another new file from the recipe: `package.json`. This is Node's `composer.json`
file: it describes the Node packages that our app needs. The most important one
is Webpack Encore itself, which *is* a Node library. It also has a few other
package that will help us get our job done.

The recipe also added an `assets/` directory... and a configuration file to control
Webpack: `webpack.config.js`. The `assets/` directory already holds a small set of
files to get us started.

## Installing Node Dependencies

Ok, with Composer, if we didn't have this `vendor/` directory, we could run
`composer install` which would tell it to read the `composer.json` file and
re-download all the packages into `vendor/`. The same thing happens with Node: we
have a `package.json` file. To *download* this stuff, run:

```terminal
yarn install
```

Or:

```terminal skip-ci
npm install
```

Go node go! This will take a few moments as it downloads everything. You'll probably
get a few warnings like this, which are safe to ignore.

Great! This did two things. First, it downloaded a *bunch* of files into the
`node_modules/` directory: the "vendor" directory for Node. It also created a
`yarn.lock` file... or `package-lock.json` if you're using npm. This serves the
same purpose of `composer.lock`: it stores the *exact* versions of all the packages
so that you get the *same* versions next time you install your dependencies.

For the most part, you don't need to worry about these lock files... except that
you *should* commit them. Let's do that. Run:

```terminal
git status
```

Then:

```terminal
git add .
```

Beautiful:

```terminal-silent
git status
```

And commit:

```terminal-silent
git commit -m "Adding Webpack Encore"
```

Hey! Webpack Encore is now installed! But... it's not doing anything yet! Freeloader.
Next, let's use it to take our JavaScript up to the next level.

# Turbo: Supercharge your App

Welcome to the *final* chapter of our intro to Symfony 6 tutorial. If you're watching
this, you're crushing it! And it's time to celebrate by installing one more package
from Symfony. But before we do, as you know, I like to commit everything first...
in case the new package installs an interesting recipe:

```terminal-silent
git add .
git commit -m "Never gonna let you go..."
```

## Installing symfony/ux-turbo

Ok, let's install the new package:

```terminal
composer require symfony/ux-turbo
```

See that "ux" in the package name? Symfony UX is a set of libraries that add
*JavaScript* functionality to your app... often with some PHP code to help. For
example, there's a library for rendering charts... and another for using an image
Cropper with the form system.

## Symfony UX Recipes

So, as you can see, this *did* install a recipe. OoOOo. Run

```terminal
git status
```

so we can see what that did. Most of this is normal, like `config/bundles.php`
means it enabled the new bundle. The two interesting changes are
`assets/controllers.json` and `package.json`. Let's check out `package.json` first.

When you install a UX package, what that *usually* means is that you're integrating
with a third-party JavaScript library. And so, that package's recipe *adds* that
library to your code. In this case, the JavaScript library we're integrating with
is called `@hotwired/turbo`. Also, the `symfony/ux-turbo` PHP package *itself* comes
with some extra JavaScript. This special line says:

> Hey Node! I want to include a package called `@symfony/ux-turbo`... but instead
> of downloading that, you can just find its code in the
> `vendor/symfony/ux-turbo/Resources/assets` directory.

You can literally look at that path: `vendor/symfony/ux-turbo/Resources/assets`
to find a mini JavaScript package. Now, because this updated our `package.json`
file, we need to re-install our dependencies to download and get this all set
up.

In fact, find your terminal that's running `yarn watch`. We've got an error!
It says the file `@symfony/ux-turbo/package.json` cannot be found, try running
`yarn install --force`.

Let's do that! Hit control+C to stop this... and then run

```terminal
yarn install --force
```

or `npm install --force`. Then, restart Encore with:

```terminal
yarn watch
```

The *other* file the recipe modified was `assets/controllers.json`. Let's go take a
look at that: `assets/controllers.json`. This is another thing that's unique to
Symfony UX. Normally, if we want to add a stimulus controller, we put it into the
`controllers/` directory. But sometimes, we might install a PHP package and *that*
may want to add its *own* Stimulus controller into our app. This syntax basically
says:

> Hey Stimulus! Go load this Stimulus controller from that new
> `@symfony/ux-turbo` package.

Now this *particular* Stimulus controller is a little weird. It's not one that
we're going to use directly inside of the `stimulus_controller()` Twig function.
This is, kind of a, fake controller. What does it do? *Just* by it being loaded,
it's going to activate the Turbo library.

## Hello Turbo! By Full-Page Refreshes

So I keep talking about Turbo. What *is* Turbo? Well, by running that composer
require command... then reinstalling yarn, the Turbo JavaScript *is* now active and
running on our site. What does it *do*? It's simple: it turns every link click
and form submit on our site into an Ajax call. And *that* makes our site feel
lightning fast.

Check it out. Do one last full refresh. And then watch... if I click Browse, there
was no full page refresh! If I click these icons, no refresh! Turbo intercepts
those clicks, makes an Ajax call to the URL, and then puts that HTML onto our site.
This is *huge* because, for free, our app suddenly looks and feels like a single
page app... without us doing anything!

## The Web Debug Toolbar & Profiler for Ajax Requests

Now, one other cool thing you'll notice is that even though full page reloads
are gone, these Ajax calls *do* show up on the web debug toolbar. And you can click
to go see the profiler for that Ajax call really easily. This Ajax part of the web
debug toolbar is even more useful with Ajax calls for an API endpoint. If we hit
the play icon... that 7 just went up to 8... and here's the profiler for that API
request! I'll open that link in a new window. That's a *super* easy way to get
to the profiler for *any* Ajax request.

So Turbo is amazing... and it can do more. There *are* some things you need
to know about it before shipping it to production, and if you're interested, yup!
We have a full tutorial about Turbo. I wanted to mention it in *this* tutorial
because Turbo is easiest if you add it to your app early on.

All right, congratulations! The first Symfony 6 tutorial is in the books! Pat yourself
on the back... or better, find a friend and give them a crisp high five.

And keep going! Join us for the next tutorial in this series, which will take you
from a budding Symfony developer to someone who *really* understands what's going
on. How services work, the point of all of these configuration files, Symfony
environments, environmental variables, and a lot more. Basically everything you'll
need to do *whatever* you want with Symfony.

And if you have any questions or ideas, we are here for you down in the comments
section below the video.

Alright friends, see you next time!

# Meet our Tiny App

Let's get to know our new project because my *ultimate* goal is for you to *really*
understand how things work. As I mentioned, there isn't a lot here yet... about
15 files. And there are really only three directories that we ever need to think
or worry about.

## The public/ Directory

The first is `public/`... and this is simple: it's the document root. In other
words, if you need a file to be publicly accessible - like an image file or a CSS
file - it needs to live inside `public/`.

Right now, this holds exactly one file: `index.php`, which is called the "front
controller". 

[[[code('f3303a0b1f')]]]

Ooo. That's a fancy word that means that - no matter what URL the
user goes to - *this* is the script that's always executed first. Its job is to
boot up Symfony and run our app. And now that we've looked at it, we'll probably
never need to think about or open it ever again.

## config/ & src/

And, really, other than putting CSS or image files into `public/`, this is *not*
a directory you will deal with on a day-to-day basis. Which means... I kinda lied!
There are really only *two* directories that we need to think about: `config/`
and `src/`.

The `config/` directory holds... kittens! Oh, I wish. Nah, it holds config files.
And `src/` holds 100% of your PHP classes. We will spend 95% of our time inside the
`src/` directory.

## composer.json & vendor/

Okay... so where is "Symfony"? Our project started with a `composer.json` file. This
lists all of the third party libraries that our app needs. The "symfony new" command
that we ran secretly used "Composer" - that's PHP's package manager - to install
these libraries... which is really just a way of saying that Composer downloaded
these libraries into the `vendor/` directory.

***SEEALSO
If you're not familiar with Composer package manager - check out our separate course
called [Wonderful World of Composer](https://symfonycasts.com/screencast/composer).
***

Symfony itself is actually a collection of a bunch of small libraries that each
solve a specific problem. In the `vendor/symfony/` directory, it looks like we
already have about 25 of these. *Technically* our app only requires these
six packages, but some of *those* packages require *other* packages... and Composer
is smart enough to download everything we need.

*Anyways*, "Symfony", or really, a set of Symfony libraries, lives in the `vendor/`
directory and our new app leverages that code to do its job. We're going to talk
more about Composer and installing third party packages later. But for the most
part, `vendor/` is yet another directory that... we don't need to worry about!

## bin/ and var/

So what's left? Well, `bin/` holds exactly one file... and will always hold just
this one file. We'll talk about what `bin/console` does a bit later. And the `var/`
directory holds cache and log files. Those files are important... but *we* will
never need to look at or think about that stuff.

Yup, we're going to live pretty much entirely inside of the `config/` and `src/`
directories.

## PhpStorm Setup

Ok, one last piece of homework before we start coding. Feel free to use whatever
code editor you want: PhpStorm, VS Code, code carrier pigeon, whatever. But I *highly*
recommend PhpStorm. It makes developing with Symfony a dream... and they're not even
paying me to say that! Though, if they *do* want to start paying me, I accept
payment in stroopwafels.

Part of what makes PhpStorm *so* great is a plugin that's designed specifically for
Symfony. I'll go to my PhpStorm preferences and, inside, find Plugins, Marketplace
then search for Symfony. Here it is. This plugin is amazing.... which you can see
because it's been downloaded 5.4 million times! It adds tons of crazy
auto-completion features that are specific to Symfony.

If you don't have it already, get it installed. Once it *is* installed, go *back*
to Settings and search up here for "Symfony" to find a new Symfony area. The one
trick about this plugin is that you need to enable it for each project. So click
that check box. Also, it's not too important, but change the web directory to `public/`.

Hit Ok and... we are ready! Let's bring our app to life by creating our very
first page next.

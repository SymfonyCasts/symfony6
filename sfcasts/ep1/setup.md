# Hello Symfony

Welcome. Hello. Hi, my name is Ryan and I have *the* absolute pleasure to introduce
you to the beautiful and fascinating and productive world of Symfony 6. Seriously,
I feel like Willie Wonka inviting you into my chocolate factory, except with
hopefully less sugar-related injuries. Anyways, if you're new to Symfony, I'm...
honestly a bit jealous! You're going to *love* the journey... and hopefully become
an even better developer along the way: you're *definitely* going to build some cool
stuff.

The secret sauce of Symfony is that it starts *tiny*, which makes it easy to learn.
But then, it scales up its features automatically through a unique recipe system.
In Symfony 6, those features include new JavaScript tools and a new security system...
just to name two of the many new things.

Symfony is also lightning fast with a huge focus on creating a joyful developer
experience, but without sacrificing programming best practices. Yea: you get to love
coding *and* love your code. I know... that sounded cheesy, but it's true.

So come with me and you'll be in a world of pure elucidation.

That's my first time singing in these tutorials... and maybe my last. Let's get started.

## Installing the "symfony" Binary

Head over to https://symfony.com/download. On this page, you'll find some
instructions - which will differ based on your operating system - on how to download
something called the Symfony binary.

This is... not actually Symfony. It's just a command line tool that will help us
*start* new Symfony projects and give us some nice local development tools. It's
optional, but I highly recommend it!

Once you've installed this - I already have - open up your favorite terminal app.
I'm using iTerm for mac, but it doesn't matter. If you got everything set up
correctly, you should be able to run:

```terminal
symfony
```

Or even better:

```terminal
symfony list
```

to see a list of all the "things" that this symfony binary can do. There's a lot
of stuff here: things that help with "local" development... and also some
optional services for deployment. We'll walk through the stuff *you* need to know
along the way.

## Let's Start a Symfony App!

Ok, so *we* want to start a brand new shiny Symfony app. To do *that* run:

```terminal
symfony new mixed_vinyl
```

Where "mixed_vinyl" is the directory the new app will be downloaded into. That's
our top-secret project to combine the *best* part of the 90's - no, not dial-up
internet, I'm talking about mix tapes - with the auditory delight of records. More
on that later.

Behind the scenes, this command uses *composer* - that's PHP's package manager - to
create the new project. More on *that* later.

The end-result is that we can move into our new `mixed_vinyl` directory. Open
this folder up in your favorite editor. I'm using PhpStorm and I *highly* recommend
it.

## Meeting our new Project

So what did that `symfony new` command do? It bootstrapped a new Symfony project!
Ooh. And we already have a git repository. Run:

```terminal
git status
```

Yep: on branch main, nothing to commit. Try:

```terminal
git log
```

Cool. After downloading the new project, the command committed all of the original
files automatically... which was *very* nice of it. Though I do wish that first
commit message was a bit more rock n' roll.

What I *really* want to show you is that our new project is super small! Try
this command:

```terminal
git show --name-only
```

Yup! Our entire project is... about 17 files. And we'll learn about all of these
along the way. But I want you to feel comfortable: there's not a lot of code here.

We're going to add features little-by-little. But if you *did* want
to start with a bigger, more fully-featured project, you can do that by running
that `symfony new` command with `--webapp`.

***TIP
If you want a fully-dockerized new Symfony app, check out
https://github.com/dunglas/symfony-docker
***

## Checking System Requirements

Before we jump into coding, let's make sure our system is ready. Run another
command from the symfony binary:

```terminal
symfony check:req
```

Looks good! If your PHP install is missing any extensions... or there are
any other problems... like your computer is actually a teapot, this will let
you know.

## Starting the Dev Web Server

So: we have a new Symfony app over here... and our system is ready! All we need now
is a subwoofer. I mean web server! You *can* set up a real web server like Nginx or
something trendy like Caddy. But for local development, the Symfony binary can help
us. Run:

```terminal
symfony serve -d
```

And... we have a web server running! Come back!

The first time you run this, it might ask you to run a different command to set
up an SSL certificate, which is nice because then the server supports https.

Moment of truth! Copy the URL, spin over to your browser, hold your breath and woo!
Hello Symfony 6 welcome page... complete with fancy color changes whenever we reload.

Next: let's meet - and become friends with - the code inside our app, so we can
demystify what each part does. Then we'll code.

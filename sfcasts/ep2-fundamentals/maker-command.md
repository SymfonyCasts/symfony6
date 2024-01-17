# MakerBundle & Autoconfiguration

Congrats, team! We are *done* with the heavy stuff in this tutorial! So it's time
for a victory lap. Let's install one of my *favorite* Symfony bundles: MakerBundle.
Find your terminal and run:

```terminal
composer require maker --dev
```

In this case, I'm using the `--dev` flag because this is a code generation utility
that we only need locally, *not* on production.

This bundle, of course, provides services. But these services aren't really meant
for us to use *directly*. Instead, all of the services from this bundle power a
bunch of new `bin/console` commands. Run

```terminal
php bin/console
```

and look for the `make` section. Ooh. There's a ton of stuff here for setting up
security, generating doctrine entities for the database (which we'll do in the next
tutorial), making a CRUD, and much more.

## Generating a new Command Class

Let's try one: how about we try to build our *own* new custom console command
that will appear in this list. To do that, run:

```terminal
php bin/console make:command
```

This will interactively ask you for the name of the command. Let's say
`app:talk-to-me`. You don't *have* to, but it's pretty common to prefix your custom
commands with `app:`. And... done!

That created exactly *one* new file: `src/Command/TalkToMeCommand.php`. Let's go
open that up:

[[[ code('01fe532ac9') ]]]

Cool! On top, you can see that the name and description of the
command are done in a PHP attribute! Then, down in this `configure()` method,
which we'll talk about more in a minute, we can configure arguments and options
that can be passed from the command line.

When we *run* the command, `execute()` will be called... where we can print things
out to the screen or read options and arguments.

Perhaps the *best* thing about this class is that... it *already* works. Check it
out! Back at your terminal, run;


```terminal
php bin/console app:talk-to-me
```

And... it's *alive*! It doesn't *do* much, but this output is coming from down
here. Woo!

## Autoconfiguration: Auto Discovering "Plugins"

But wait... how *did* Symfony instantly see our new `Command` class and know to
start using it? Is it because it lives in the `src/Command/` directory... and
Symfony scans for classes that live here? Nope! We could rename this directory to
`ThereAreDefinitelyNoCommandsInHere`... and Symfony would *still* see the command.

The way this works is *much* cooler. Open up `config/services.yaml` and
look at the `_defaults` section:

[[[ code('99a7530291') ]]]

We talked about what `autowire: true` means, but I didn't explain the purpose of 
`autoconfigure: true`. Because this is below `_defaults`, autoconfiguration *is* 
active on all of our services, including our new `TalkToMeCommand` service. 
When `autoconfiguration` is enabled, it basically tells Symfony:

> Hey, please look at the base class or interface of each service, and if it
> *looks* like a class should be a console command... or an event subscriber...
> or any other class that hooks into a part of Symfony, please automatically
> integrate the service *into* that system. Okay, thanks. Bye!

Yep! Symfony sees that our class extends `Command` and thinks:

> Hmm, I may not be a self-aware AI... but I bet this is a command. I better notify
> the console system about it!

I *love* autoconfiguration. It means that we can create a PHP class, extend
whatever base class or implement whatever interface needed for the "thing" that
we're building, and... it will just *work*.

Internally, if you want all the nerdy details, autoconfiguration adds a *tag* to
your service, like `console.command`, which is what ultimately helps it get noticed
by the console system.

All right, now that our command is working, let's have some fun and customize it
*next*.

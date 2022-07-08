# Maker Command

Congrats, team! We are *done* with the heavy stuff in this tutorial, so it's time for a victory lap. Let's install one of my *favorite* Symfony bundles: MakerBundle. Find your terminal and run:

```temrinal
composer require maker --dev
```

In this case, I'm using the `--dev` flag because this is a code generation utility that we only need locally, *not* on production.

This bundle, of course, provides services, but these services aren't really meant for us to use *directly*. Instead, all of the services from this bundle power a bunch of new `bin/console` commands. Run

```terminal
php bin/console
```

and look for the `make` section. Ooh, there's a ton of stuff in here for setting up security, generating doctrine entities for the database (which we'll do in the next tutorial), making a CRUD, and much more. And *soon*, there may even be a new `make:scaffold` command, which will help you quickly generate even more large features of your projects, like bootstrapping a functional site with styling that has registration, a password reset, and log in *right* out of the box.

Anyway, let's try one of these commands. I'm actually going to create a new class where we can run a new command from this list. To do that, run:

```terminal
php bin/console make:command
```

This will interactively ask you what you want the command to be. Let's say `app:talk-to-me`. You don't *have* to, but it's pretty common to prefix your custom commands with `app:`. And... done! That created exactly *one* new class: `src/Command/TalkToMeCommand.php`. Let's go open that up. Cool! On top, you can see that the name of the command and a description is actually done in a PHP attribute. And then down in this `configure()` method, which we'll talk about more in a second, we can configure arguments and options that are passed to it. When we *run* the command, `execute()` will be called, which will allow us to print things out on the screen and read in options and arguments. The really cool thing about this class is that it *already* works. Check this out! Back at your terminal, run:

```terminal
php bin/console app:talk-to-me
```

And... it's *alive*! It doesn't do too much, but this command is actually coming from down here. Woo!

But wait... how did Symfony instantly see our new `Command` class and know that it should start using it? Is it because it lives in this `/Command` directory and Symfony scans for classes that live here? Nope! We could rename this directory to "/ThereAreDefinitelyNoCommandsInHere" and a would *still* see the command.

The way this works is actually *much* cooler. Open up `/config/services.yaml` and look at the `_defaults` section. We talked about what `autowire: true` means, but I didn't explain the purpose of `autoconfigure: true`. Because this is under `_defaults`, autoconfiguration *is* active on all of our services, including our new `Command` service. When autoconfiguration is enabled, it basically tells Symfony:

`Hey, please look at the base class or interface
of each service, and if it looks like a class
should be a console command, event subscriber, or
any other class that hooks into Symfony,
automatically integrate me into that system.
Okay, thanks. Bye!`

Yep! Symfony sees that our class extends `Command` and thinks:

`I bet this is a command. I better notify the
console system about this.`

I *love* autoconfiguration. It means that we can just create a PHP class, extend whatever base class or implement whatever interface we need for the thing we're building, and it will just *work*. Internally, if you want all the nerdy details, autoconfiguration adds a tag to your service, like `console.command`, which is what ultimately helps it get noticed by the console system.

All right, now that our command is working, let's have some fun and customize it *next*.

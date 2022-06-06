# Maker Command

Coming soon...

Congrats team. We are done with the heavy stuff in this tutorial. So time for a
victory lap, let's install one of my favorite Symfony bundles maker bundle. Find your
terminal and run `composer require maker --dev` In this case, I'm using `--dev`
because this is a code generation utility That we only need locally, but we don't
need it installed on production. The `--dev` flag isn't that important, but that's
why we're using it here. So this bundle of course provides services, but these
services aren't really meant for us to use directly. Instead, all the services from
this bundle are meant to power, a bunch of new bin console commands run `php
bin/console` and look for the make section. Ooh, There's a ton of stuff in here for
setting up security generating doc doctrine entities for the database, which we'll do
in the next tutorial making a crud in lots of other stuff. And soon there may even be
a new make scaffold command, which will help generate even more big parts of your
site for new projects, Like quickly bootstrapping a functional site With styling that
has registration reset password and log in.

All right, anyways let's try one of these commands. What I'm going to do is actually
create a new console command. So I'm actually going to create something that, uh, a
new class where we can actually run a new command in this list to help us do that. We
can make this run, this make command, command. So run `Php bin/console make:command`.
This will interactively ask you what we want the command to be. Let's do `App:talk
-to-me`. You don't have to, but it's pretty common to prefix your custom commands
with `App:` and done that created exactly one new class
`src/command/TalkToMecommand`. Let's go open that up and Cool. So, a couple things on
top, you can see that the name of the command and a description is actually done in a
`php attribute` and then down in this configure method, which we'll talk about more
in a second, we can configure arguments and options that pass to it. When we actually
run the command `Execute()`, what'll be called and we can print things out to the
screen, read in options and arguments. Now here's the really cool thing about this
class. It all already works. Check this out, back your terminal.

Run `php bin/console app:Talk-to-me` and It's alive. It doesn't do too much, but this
command is actually coming from down here. Woo. But wait, how did Symfony instantly
see our new command class and know that it should start using it? Is it because it
lives in this command directory and Symfony scans for classes that live here? Nope.
We could rename this directory to, there are definitely no commands in here and a
would still see the command the way this works is way cooler, open up a config
`services.yaml` And look at the `_default` section. We talked about what `autowire:
true` means, But I didn't explain the purpose of `autoconfigure: true` because this
is `_defaults: autoconfiguration` is active on all of our services, including our new
command service. When auto configuration is enabled a basically tells Symfony Hey,
please look at the base class or interface of each service. And if it looks like a
class should be a console command or an event subscriber or any other class that
hooks into Symfony, please automatically integrate me into that system then. Thanks,
bye. Yep. Symfony sees that our class extends command

And thinks, I bet this is a command. I better notify the console system about this. I
love auto configuration. It means that we can just create a Php class, extend
whatever base class or implement whatever interface we need for whatever thing we're
building. And it will just work internally. If you want the nerdy details. Auto
configuration adds a tag to your service like console.command, which is what
ultimately helps it get noticed by the console system. All right, now that our
command is working, let's have some fun and customize it next.

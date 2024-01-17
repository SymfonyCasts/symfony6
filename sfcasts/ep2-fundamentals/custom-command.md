# Customizing a Command

We have a new console command! *But*... it doesn't do much yet, aside from printing
out a message. Let's make it *fancier*.

Scroll to the top. This is where we have the name of our command, and there's also
a description... which shows up next to the command. Let me change ours to

> A self-aware command that can do... only one thing.

[[[ code('0665b762f7') ]]]

## Configuring Arguments and Options

Our command is called `app:talk-to-me` because, when we run this, I want to make
it possible to pass a name to the command - like Ryan - and then it'll reply with
"Hey Ryan!". So, literally, we'll type `bin/console app:talk-to-me ryan` and it'll
reply back.

When you want to pass a value to a command, that's known as an *argument*... and
those are configured down in... the `configure()` method. There's already an
argument called `arg1`... so let's change that to `name`.

This key is completely *internal*: you'll never see the word `name` when you're
*using* this command. But we *will* use this key to *read* the argument value in
a minute. We can also give the argument a description and, if you want, you can
make it *required*. I'll keep it as optional.

The next thing we have are *options*. These are like arguments... except that
they start with a `--` when you use them. I want to have an optional flag where
we can say `--yell` to make the command *yell* our name back.

In this case, the name of the option, `yell`, *is* important: we *will* use
this name when passing the option at the command line to use it. The
`InputOption::VALUE_NONE` means that our flag will just be `--yell` and not
`--yell=` some value. If your option accepts a value, you would change this to
`VALUE_REQUIRED`. Finally, give this a description.

[[[ code('8001eef9d6') ]]]

Beautiful! We're not *using* this argument and option yet... but we can already
re-run our command with a `--help` option:

```terminal
php bin/console app:talk-to-me --help
```

And... awesome! We see the description up here... along with some details about
how to use the argument and the `--yell` option.

## Filling in execute()

When we call our command, very simply, Symfony will call `execute()`... which
is where the fun starts. Inside, we can do *whatever* we want. It passes us
two arguments: `$input` and `$output`. If you want to read some input - like the
`name` argument or the `yell` option, use `$input`. And if you want to *output*
something, use `$output`.

But in Symfony, we normally pop these two things into *another* object
called `SymfonyStyle`. This is helper class makes reading and outputing
easier... and fancier.

Ok: let's start by saying `$name = $input->getArgument('name')`. If we don't
have a name, I'll default this to `whoever you are`. Below, read the
option: `$shouldYell = $input->getOption('yell')`:

[[[ code('c25283b263') ]]]

Cool. Let's clear out this stuff down here and start our message:
`$message = sprintf('Hey %s!', $name)`. Then if we want to yell, you know what
to do: `$message = strtoupper($message)`. Below, use `$io->success()` and
put the message there.

[[[ code('e396b4e104') ]]]

This is one of the many helper methods on the `SymfonyStyle` class that help
format your output. There's also `$io->warning()`, `$io->note()`, and several others.

Let's try it. Spin over and run:

```terminal
php bin/console app:talk-to-me ryan
```

And... oh hello there! If we yell:

```terminal-silent
php bin/console app:talk-to-me ryan --yell
```

THAT WORKS TOO! We can even yell at 'whoever I am':

```terminal-silent
php bin/console app:talk-to-me --yell
```

Awesome! But let's get crazier... by autowiring a service and asking a question
*interactively* on the command line. That's next... and it's the last chapter!

# New Component: Scheduler

One of the coolest new components is Scheduler, which came from Symfony 6.3. If you
need to trigger a recurring task, like generate a weekly report, send some sort
of heartbeat every 10 minutes, perform routine maintenance... or even something
custom and weird, this component is for you. It's really neat! It deserves its
own tutorial, but we'll worry about that later. Let's take it for a test drive.

## Installing Scheduler

At your command line, install it with:

```terminal
composer require symfony/scheduler symfony/messenger
```

Scheduler relies on Messenger: they work together! The process looks like this. You
create a message class and handler, like you normally would with
Messenger. Then you tell Symfony:

> Yo! I want you to send this message to be handled every seven days, or every
> one hour... or something weirder.

## Creating the Message Class & Handler

This means that step one is to generate a Messenger message. Run:

```terminal
php bin/console make:message
```

Call it `LogHello`. Cool! Over here, it created the message class - `LogHello` 

[[[ code('9fd176c05a') ]]]

and its handler, whose `__invoke()` method will be called when `LogHello` is dispatched
through Messenger.

[[[ code('cd839e5361') ]]]

In `LogHello`, give it a constructor with `public int $length`. 

[[[ code('787cd14c73') ]]]

This will help us figure out which message is being handled and when. In the handler, 
*also* add a constructor so we can autowire `LoggerInterface $logger`.

[[[ code('a3c92cd450') ]]]

Down in the method, use `$this->logger->warning()` - just so these log entries
are easy to see - then `str_repeat()` to log a guitar icon `$message->length`
times. I'll also log that number at the end.

[[[ code('5cde3a301b') ]]]

Message & handler check!

## Creating the Schedule

Next up is to create a *schedule* that tells Symfony:

> Yo, me again. Please dispatch a `LogHello` message through messenger every
> 7 days.

Or in our case, every few seconds because I don't think you want to watch this
screencast for the next week! 

In `src/`, I don't have to do this, but I'll create a `Scheduler` directory.
And inside, a PHP class called, how about, `MainSchedule`. Make this implement
`ScheduleProviderInterface`.

[[[ code('0191745407') ]]]

You can have multiple of these schedule providers in your system... or you can
have one class that sets up *all* your recurring messages. Your call.

This class also needs an attribute called `#[AsSchedule]`. This has one optional
argument: the schedule name, which, creatively, defaults to `default`. We'll see
why that name is important soon. I'll use `default`.

[[[ code('651134046e') ]]]

## Creating the Recurring Messages

Ok, go to Code -> Generate, or command+N on a Mac - to implement the one method we
need: `getSchedule()`. 

[[[ code('ab2732d408') ]]]

The code in here is beautifully simple and expressive.
Return a `new Schedule()`, then add things to this by calling `->add()`.
Inside, for each "thing" you need to schedule, say `RecurringMessage::`.
There are several ways to create these recurring messages. The easiest is `every()`,
like every `7 days` or every `5 minutes`. You can also pass a `cron` syntax,
or call `trigger()`. In that case, you would define your *own* logic
for *exactly* when you want your weird message to be triggered.

Use `every()` and pass `4 seconds`. Every 4 seconds, we want this
new `LogHello` message to be dispatched to Messenger. Copy that, then create
another for every `3 seconds`.

[[[ code('e9b9ccbf5f') ]]]

We're done!

## Consuming the Scheduler Transport

The *result* of creating a schedule provider is that a new Messenger *transport*
is created. To get your recurring messages to process, you need to have a worker
that's running the `messenger:consume` command.

At your terminal, run `bin/console messenger:consume` with a `-v` so we can see
the log messages from our handler. Then pass the name of the new, automatically-added
transport: `scheduler_default`... where `default` is the *name* we used in the
`#[AsSchedule]` attribute.

```terminal-silent
php bin/console messenger:consume -v scheduler_default
```

Hit it, wait about 3 seconds... there it is! Four! Then the 3 one comes up again,
and four, then three. After 12 seconds, they should execute, yep, at almost
the exact same moment. Technically, this one was dispatched first, and *then* that
one was dispatched immediately after.

But, let me stop nerding out and back up: it's working! It's beautiful!

## How does Scheduler Work?

*How* is it working? I wondered that same thing. When the worker command starts,
it loops over every `RecurringMessage`, calculates the next runtime of each,
and uses that to create a list - called the "heap" - of upcoming messages. Then it
loops forever. As soon as the current time matches - or is later than - the scheduled
runtime of the next message in the heap, it takes that message and dispatches it
through Messenger. It *then* asks this recurring message for its *next* runtime and
puts *that* inside the heap.

And this process just... continues forever.

## Make your Schedule Stateful

Though there is one problem hiding in plain sight: if we restart the command, it
creates the schedule from scratch. That means that it waits a fresh *new* three
seconds and four seconds before it dispatches the messages.

In a real app, this will be a problem. Imagine you have a message that runs every
seven days. For some reason, after 5 days, your `messenger:consume` command
exits and is restarted. Because of this, your recurring message will now run
seven days *after* this restart: so it will run on day 12. If it keeps getting
restarted, your message may *never* run!

This is *not* workable. And so, in the real world, we always make our schedule
*stateful*. And this easy. Create a `__construct` method and autowire a
`private CacheInterface`: the one from Symfony cache.

[[[ code('ac7a77fd10') ]]]

Down below, call `->stateful()` and pass `$this->cache`.

[[[ code('67af97e263') ]]]

Also, open `services.yaml`. In an earlier tutorial, I added some config that
effectively disabled the cache in the `dev` environment. Remove that so we have
a proper cache.

Ok, stop the worker and restart it. The first time we do this, it's
going to have the same behavior as before: wait three seconds and four seconds.
There we go.

But now, stop this, wait a few seconds and watch what happens when I restart. It
catches up! Those messages happened immediately!

The state keeps track of the last time Scheduler checked for messages. And so,
if your worker gets turned off for a bit, when it restarts, it *reads* that time
and uses it as its starting time so it can catch up with all the messages that it
missed.

It does mean that you may have some messages that are executed multiple times
immediately, but it won't miss anything.

## Multiple Workers: Lock your Schedule

Oh, and if you plan to have multiple workers for your scheduler transport, you'll
also need to add a *lock* to the schedule. This is easy and covered in the docs:
autowire the lock factory, then call `->lock()` to pass in a new lock. This will
make sure that two workers don't grab the *same* recurring message at the same time
and *both* process it.

All right team, that's all I've got! Thanks for hanging out. If you have any questions
about upgrading or hit a problem we didn't mention, we're here for you down in the
comments. And let us know if you have a victory: we love hearing success.

All right, friends. See you next time!

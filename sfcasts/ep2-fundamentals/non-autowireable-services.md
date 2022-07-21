# Non-Autowireable Services

Run:

```terminal
php bin/console debug:container
```

And... I'll make this a bit smaller so that everything shows up on one line. As we
know, this command shows *all* of the services in our container... but only a *small*
number of these are autowireable. We know that because a service is autowireable
*only* if its ID, which is this over here, is a class or interface name.

So at first, it might look like the Twig service is *not* autowireable. After all,
its ID - `twig` - is definitely *not* a class or interface. But if you scroll up
to the top... let's see... yep! There's another service in the container whose ID
is `Twig\Environment`, which is an *alias* to the service `twig`. This is a little
trick Symfony does to make services autowireable. If we type-hint an argument
with `Twig\Environment`, we get the `twig` service.

However, most of services in this list do *not* have an alias like that. So they
are *not* autowireable. And, that's *usually* fine. If a service *isn't* autowireable,
it's probably because you'll never need to use it. *But* let's pretend that we *do*
want to use one of these.

Check this one out! It's called `twig.command.debug`. Open up another tab.
Earlier, we ran:

```terminal
php bin/console debug:twig
```

This shows us *all* of the functions and filters from Twig... which is nice!
Well, surprise! This command *comes* from the `twig.command.debug` service! Because,
"everything in Symfony is done by a service" - even console commands.

As a challenge, let's see if we can inject this service into `MixRepository`,
execute it, and dump its output.

## Dependency Injection: Adding the new Argument

First things first. In `MixRepository`, we just discovered that, in order to do our
work, we need access to another service. What do we do? The answer: *Dependency
injection*, which is that fancy word for adding another construct argument and
setting it onto a property, which we can do all at once with
`private $twigDebugCommand`:

[[[ code('db9765d0f6') ]]]

If we stopped right now and refreshed... no surprise! We get an error. Symfony has
no idea what to pass for that argument.

What if we added the *type* for this class? Back over in our terminal, we can see
that this service is an instance of `DebugCommand`. Over here, let's add that
type-hint: `DebugCommand`... we want the one from `Symfony\Bridge\Twig\Command`.
Hit "tab" to autocomplete that:

[[[ code('1d003f5f47') ]]]

And then... refresh. *Still* an error! Okay, we *should* add the type-hint because
we're good programmers. But... no matter how hard we try, this is *not* an autowireable
service. So, how do we fix this?

## Binding the Argument in YAML

There are two main ways. I'll show you the *old* way first, which I'm *mostly* doing
because you'll see it in documentation and blog posts all over the place. In
`config/services.yaml`, just like we did earlier for the `$isDebug` argument,
override our service entirely. Say `App\Service\MixRepository`, and add a
`bind` key. Then, we're going to hint what to pass to the `$twigDebugCommand` argument.

The *only* tricky thing is figuring out what *value* to set. For example, if I go
and copy the service ID - `twig.command.debug` - and paste that here... that's *not*
going to work! That's literally going to pass that *string*. If you refresh,
yup!

> Argument 4 must be of type `DebugCommand`, string given.

We need to tell Symfony to pass the *service* that has this ID. In these YAML
files, there's a special syntax to do just that: *prefix* the service ID with the
`@` symbol:

[[[ code('447697206a') ]]]

As *soon* as we do that... the fact that this doesn't explode means it's working!

## The Autowire Attribute

*But*... let's remove this. Because I want to show you the *new* way do this...
which leverages that same fancy `Autowire` attribute.

Up here, say `#[Autowire()]`, but instead of just passing a string, say
`service: 'twig.command.debug'`:

[[[ code('b2b20613e3') ]]]

## Using the new Argument

I love that! Before we try this, let's actually *use* the service. Head down to
`findAll()`. Executing a console command *manually* in your PHP code is totally
possible. It's a little weird, but cool! We need to create an
`$output = new BufferedOutput()` object... then we can execute the command by saying
`$this->twigDebugCommand->run(new ArrayInput())` - this is, sort of, *faking*
the command-line arguments - pass that an empty `[]` - then `$output`. Whatever
the command *outputs* will be set onto that object.

To see if it's working, just `dd($output)`:

[[[ code('d77a8d195f') ]]]

Testing time! Refresh... and got it! How fun is that?

All right, now that this is working, let's comment out this silliness. I'll keep our
`$twigDebugCommand` injected just for reference.

The *key* takeaway is this: *most* arguments to services will be autowireable. Yay!
But when you hit an argument that is *not* autowireable, you can use the `Autowire`
attribute to point to the value or service you need.

Next: Remember when I told you that `MixRepository` was the first service we ever
created? Well... I lied. It turns out that our *controllers* have been services this
whole time!

# Non-Autowireable Services

Run:

```terminal
php bin/console debug:container
```

And I'll make this a little smaller so that everything shows up on one line. As we know, this command shows *all* of the services in our container, and only a *small* number of these are autowireable. We know that because a service is autowireable *only* if it's ID, which is this over here, is a class or interface name. So at first, it might look like the Twig service is not autowireable. After all, its ID - `Twig` - is definitely *not* a class or interface. But if you scroll up to the top... let's see... yep! There's another service in the container whose ID is `Twig\Environment`, which is an alias to the service `twig`. This is a little trick Symfony does to make services autowireable. As long as we type-hint an argument with `Twig\Environment`, we get the Twig service.

However, most of the services in this list do not have an alias like that, so they are *not* autowireable, and that's *usually* fine. If a service *isn't* sutowireable, it's probably because you'll never need to use it. *But* let's pretend that we *do* want to use one of these services.

Check this one out! It's called `twig.command.debug`. This is the service that's behind the `debug:twig` command. Let's open up another tab here. Earlier, we ran:

```terminal
php bin/console debug:twig
```

It shows us *all* of the functions and filters. Well, that comes from this service! As a challenge, let's see if we can inject this service into our `MixRepository`, then execute that command and dump out the result.

First things first. In `MixRepository`, we just discovered that in order to do our work, we need access to another service. What do we do? The answer: *Dependency injection*, which is that fancy word for adding another constructive argument and setting it onto a property, which we can do all at once with `private $twigDebugCommand`. If we stopped right now and refreshed... no surprise. We get an error. Symfony has no idea what to pass for that argument.

What if we added the *type* for this class? Back over in our terminal, you can see that this service is an instance of this `DebugCommand`. Over here, let's add a type to it: `DebugCommand`. We want the one from `Symfony\Bridge\Twig\Command`. Hit "tab" to autocomplete that, and then... refresh. *Still* an error! Okay, we *should* add the type because we're good programmers, but no matter how hard we try, this is *not* an autowireable service. So how do we fix this? There are two main ways.

I'll show you the *old* way first, which I'm *mostly* doing because you'll see this in documentation and blog posts all over the place. In `config/services.yaml`, just like we did earlier with the `$isDebug` argument, we're going to override our service entirely. Say `App\Service\MixRepository` and add a `bind` key. Then, we're going to hint what to pass to the `$twigDebug` argument. The only tricky thing here is figuring out what value to set. For example, if I go and copy the service ID - `twig.command.debug` - and paste that here, that's *not* going to work. That's literally going to pass us that string. If you refresh, you can see that it says:

`Argument #4 ($twigDebugCommand) must be of type
Symfony\Bridge\Twig\Command\DebugCommand, string
given [...]`

We need to tell Symfony that we want to pass the *service* that has this ID, and in these `.yaml` files, there's a special syntax to do just that. It's *prefixing* the service ID with the `@` symbol. As soon as we do that, the fact that this doesn't explode means it's working. *But* I want you to remove this, because I'm going to show you the *new* way we do this, leveraging that same fancy autowire attribute.

Up here, say `#[Autowire()]`, and instead of just passing at a string, we're going to say `service: 'twig.command.debug'`. I love that! Before we try this, let's actually *use* the service. Head down to `findAll()`.

Executing a service *manually* in your PHP code is totally possible. It's a little weird, but it's super fun. We need to create this `$output = new BufferedOutput()` object, and then we can call it by saying `$this->twigDebugCommand->run(new ArrayInput())`. This is, sort of, *faking* the arguments that we're passing to the method. Pass that empty flags with `[]`, `$output`, and then set whatever the command output is going to be on this variable. So let's just `dd($output)`. If we go refresh... got it! How fun is that?

All right, now that this is working, let's comment out this silliness. I'll keep our `$twigDebugCommand` injected just for reference. The key takeaway is this: *Most* arguments to services are autowireable. Yay! When you hit an argument that is *not* autowireable, use the autowire attribute to point to the value or service you need.

Next: Remember when I told you that `MixRepository` was the first service we ever created? Well... I lied. It turns out that our *controllers* have been services this whole time.

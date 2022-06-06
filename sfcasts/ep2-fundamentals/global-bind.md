# Global Bind

Coming soon...

In practice, you rarely need to do anything inside of`"services.yaml` 99% of the time
when you need an argument in a service it's Autowireable. So you add the argument
with the type in and keep coding. But for the isDebug argument is Autowireable since
it's not a service and that forced us To need to completely override the service and
specify that one argument with bind. It works but lame. So here's another solution
that I like Copy that buy and key delete the service entirely, And then head up under
`_defaults` and paste. When we move over and try this, the page still works. And that
makes sense. Think about it. This line down here is going to automatically register
our mix repository as a service, then anything under `_default` is going to be
automatically applied to that service. So the end result is exactly what we had
before. I love doing this. It allows me to set up project wide conventions. Thanks to
this. I can now add an isDebug argument to any, to the construction of any service in
it will work By the way, if you want to, you can also include

The type here. So this would now only work. If we use the `bool` Ah uh, type end
inside of our argument. If I used string here, for example, it wouldn't try to pass
in that value. Okay? So the global bind is awesome, but starting in symfony 6.1,
there's another way to specify a non-autowireable argument. Comment out this global
bind. I still like doing this, but let's try this other way. If we refresh right now,
we get the error because symfony doesn't know what to pass to the isDebug argument.
Two, fix that, go into our mix repository service and above the argument, or before
the argument, if you're not using multiple lines, add a PHP eight attribute It's
called Autowire. Now normally a Php eight attributes will auto-complete notice this
isn't auto-completing for me. This is actually due to a bug and PHP storm currently.
So I'm going to type out auto wire and then just go up and type autowire up here and
hit tab to auto, complete to auto, complete that. And then if you want to make them
alphabetical, you can,

Oh,

You can Into autowire. And you also notice that it's underlying it and says the
attribute cannot be applied to a property. But Php storm is just confused, cuz this
is both a property and in the argument to the method anyways, pass this
`('%kernel.debug%')` Refresh now and got it pretty cool. Right. All right. So most of
the time when we're, when you autowire an argument like HTTP client interface,
there's only one service in a container that implements that interface. But what if
there were multiple? What if there were multiple HTTP clients in our container and we
needed to be able to choose the one we want. Let's talk about named autowiring next.

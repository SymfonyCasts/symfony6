# debug:container & How Autowiring Works

Ok, I lied. *Before* we talk about environments, I need to come clean about
something: I have *not* been showing you all of the services in Symfony. Not
even close.

Head over your terminal and run our *favorite* command:

```terminal
php bin/console debug:autowiring
```

We know that all of these services are floating around in Symfony, waiting for
us to ask for them. *And* we know that bundles give us services. The Twig service
down here comes from TwigBundle.

And since each service is an *object*, something *somewhere* must be responsible
for *instantiating* these objects. The question is: "Who?" And the answer is...
the service container!

## Hello Service Container

It turns out that all of the services aren't really... "floating around": they
all live inside something called the "container". And there are *way* more services
in the container than `debug:autowiring` has been telling us about. Ooh... secrets!
This time, run:

```terminal
php bin/console debug:container
```

And... whoa! This prints out a *huge* list. It's so big, it's hard to see everything.
Let me make my font smaller. Much better!

*This* is the full list of all of the services in our app... or in the
"container". The container is basically a giant "array" where each service
has a unique name that points to its service object. For example, down here... there
we go... we can see that there's a service whose unique name - or "id" is `twig`.

Knowing that the id of the Twig service is `twig` is not *usually* important, but
it *is* useful to understand that each service has a unique id... and that you can
see all of them inside the `debug:container` command.

## The Container Creates Objects

And really, the container might be better-described as a big array of *instructions*
on how to instantiate services, *if* and *when* something asks for them. For instance,
the container knows *exactly* how to instantiate this Twig service. It knows that
its class is `Twig\Environment`. And even though you can't see it on this list, it
knows the *exact* arguments to pass to its constructor. The moment someone
*needs* the Twig service, the container instantiates it and returns it.

Yup, when we autowire a service, we're basically saying:

> Hey container, can you please give me the HTTP Client service?

If nothing in our code has asked for that service yet during this request, the
container will create it. But if something *has* already asked for it, then the
container will simply return the one it *already* created. This means that if we
ask for the HTTP Client service in *ten* different places, the container will only
create and return the same *one* instance. Pretty cool!

## How Autowiring Works

Anyway, `debug:container` shows us *all* of the services that the container knows
how to instantiate. *But* `debug:autowiring` only shows us a *fraction* of those
services. Why?

Well, it turns out that not *all* services are autowireable. Many of the items in
this list are low-level services that just exist to help *other* services do their
job. You'll probably never need to use these low-level services directly... and
you actually *cannot* fetch them via autowiring.

But, let's back up a minute. Now that we know a bit more, we can now learn exactly
*how* Symfony's autowiring system works. It's beautifully simple.

As we've seen, the container is really an array where every service has an
id that points to that service object. When Symfony sees this `HttpClientInterface`
type - this is the full type that it sees, thanks to our `use` statement - in order
to figure out *which* service in the container it needs to pass us, it simply looks
for a service whose *ID* matches this string *exactly*. Let me show you!

Scroll towards the top of this list to find... a service whose ID is
`Symfony\Contracts\HttpClient\HttpClientInterface`! The *vast* majority of the
services in the container use the "snake case" naming strategy. But if a service
is intended for *us* to use in our code, Symfony will add an *additional* service
inside that matches its class or interface name.

Thanks to that, when we type-hint `HttpClientInterface`, Symfony looks in the
container for a service whose id is
`Symfony\Contracts\HttpClient\HttpClientInterface`, it finds it and passes it
to us.

## Service Aliases

But look over on the right side: it says that this is an alias for
a different service ID. An "alias" is like a symbolic link. It means that
when someone asks for the `HttpClientInterface` service, Symfony will *actually*
pass us this *other* service.

We can use the same logic down here for the `CacheInterface` type. If we check
the list, here's the service whose id matches that type. But, in reality, it's
just an alias for a service called `cache.app`. So when we autowire `CacheInterface`,
the `cache.app` service is what's *actually* being passed to us.

If you're feeling unsure, here are the three big takeaways. One: there are a *ton*
of service objects floating around and they all live inside something called the
"container". Each service has a unique id.

Two, only a *small* percentage of these are useful to us... and those are set up
so that we can autowire them. Autowiring works by looking in the container for a
service whose id exactly matches the type. When we run `debug:autowiring`, it's
basically just showing us the services from this list whose id is a class or
interface name. Those are the "autowireable services".

The third and final takeaway is that services also have an alias system... which
just means that when we ask for the `CacheInterface` service, what it will *really*
give us is the service whose id is `cache.app`.

If you're wondering how we could ever use a *non-autowireable* service in our code,
that's a great question! It's somewhat rare, but we *will* learn how to do that
later.

Next, let's talk about using different configuration locally versus production.
Let's talk about *environments*.

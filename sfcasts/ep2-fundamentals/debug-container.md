# Debug Container

Head over your terminal and run our *favorite*:

```terminal
php bin/console debug:autowiring
```

We know that all of these services are just floating around in Symfony, waiting for us to ask for them. And we know that bundles give us services. The Twig service down here comes from Twig bundle. *But* these services are *objects*. This is an `$httpClient` object. And this is a `$cache` object. So something *somewhere* must be responsible for instantiating these objects. The question is: "What?" And the answer is... the service container.

Like I said earlier, Symfony has a bunch of services floating around. It turns out, these services all live inside something called the "container", and there are *way* more services in the container than `debug:autowiring` has been telling us about. Ooh... secrets! This time, run a different command. Instead of `debug:autowiring`, run:

```terminal
php bin/console debug:container
```

And... whoa! This prints out a *huge* list. In fact, it's so big, it's hard for me to see everything. Let me make my font smaller. Perfect! This is the full list of all of the services currently in the container. The container is basically a giant key value pair where each service has a unique name that points to its service object. For example, down here... there we go... you can see that there's a service whose machine name is `twig`, and that is the Twig service. Knowing that the machine name of the Twig service is `twig` is not *usually* important, but it *is* useful to understand that each service has a unique name, and you can see all of them inside this `debug:container` command.

The container is probably better described as a big array of instructions on how to instantiate services, *if* and *when* something asks for them. For instance, the container knows *exactly* how to instantiate this Twig service. It knows that its class is `Twig/Environment`, and even though you can't see it on this list, it knows the *exact* arguments to pass to the constructor to Twig environment. It knows the first, second, *and* third argument to pass. It knows *everything* about how to create that object. And the moment someone needs this service, the container creates it and then returns it. That's what happens when we autowire. We're basically saying:

`Hey container, can you please give me the HTTP
Client service?`

If nothing in our code has asked for that service yet during this request, the container will create it. But if something has *already* asked for the service during the request, then the container will simply return the one it already created. So if we ask for the HTTP Client service in *ten* different places, it will only create *one* instance. Pretty cool!

Anyway, `debug:container` shows us all of the services the container knows how to instantiate. *But* `debug:autowiring` only shows us a *fraction* of those services. Why? Well, it turns out that not *all* services are autowireable. Many of the services inside of here are super low level services that just exist to help other services do their job. You'll probably never need to use these low level services directly, and you can't fetch them via autowiring. This might be a little confusing, so let me back up and explain how Symfony's autowiring system works. It's beautifully simple.

As we've seen, the container is really a key value pair where every service has a machine name that points to that service object. When Symfony sees this `$httpClient` interface typehint for autowiring (That's the full type that is sees, thanks to our use statement), in order to figure out which service in the container it needs to pass you, it simply looks for a service whose ID matches this string *exactly*. Let me show you!

If I scroll towards the top of this list, you'll see a service whose ID is `Symfony\Contracts\HttpClient\HttpClientInterface`. The vast majority of the services in the container use this snake case naming strategy. But if a service is meant for you to use in your code, Symfony will add an *additional* service inside of here that matches the class or interface name. When we typehint `HttpClientInterface`, Symfony looks in the container for a service that matches this ID. It finds this service and passes it to us. If you'll notice, over here on the right, it says that this is an alias for a different service ID. An "alias" is almost like a symbolic link. It means that when someone asks for the `HttpClientInterface` service, Symfony's actually going to pass us whatever this service is here, which is one of the services in this list. We can do the same thing down here for the `CacheInterface` typehint. If we check the list, here's its service ID, and *this* is the alias for the ID called `cache.app`. So when we autowire `CacheInterface`, the `cache.app` service is what's *actually* being passed to us. Down here, you can see that the `cache.app` service is actually an instance of this `TraceableAdapter`.

All right, I know that this was... quite a bit, but here are the takeaways. There are a *ton* of service objects floating around and they all live inside something called the "container". *But* only a small percentage of these are actually useful to us. And the most useful ones are set up so that we can autowire them. So effectively, when we run `debug:autowiring`, it just shows us the services from this list that start with a class or interface name. If I run this again, we see that same list here in a more user friendly way. If I run `debug:autowiring` again and search for "cache", you can see the `CacheInterface` that we're autowiring into our controller, and it shows you that this is an alias to the service whose ID is `cache.app`. So again, it's not *super* important to understand that, but now you know that this is the typehint you use, and this is the ID of the service that you're going to get if you use it.

If you're wondering how we could use a non-autowireable service in our code, that's a great question! That's pretty rare, but we'll talk about that in more detail later. Most of the time, you're going to be using one of the services from `debug:autowiring`.

Next, let's talk about using different configuration locally versus production. Let's talk about *environments*.

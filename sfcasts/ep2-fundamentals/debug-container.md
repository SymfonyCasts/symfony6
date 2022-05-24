# Debug Container

Coming soon...

Head of your terminal and run our favorite Bin console, Debug auto wiring. We know
that there are all these services are just floating around in Symfony, waiting for us
to ask for them. And we know that bundles give us services. The twig service down
here comes from twig bundle, but these services are objects. This is an HTP client
object. This is a cache object. So something somewhere must be responsible for
instantiating these objects. The question is who And the answer is the service
container. So I keep saying, Symfony has a bunch of services floating around. Well,
it turns out these services all live inside. Something called the container And it
turns out there are way more services in the container than debug auto wiring has
been telling us about, Ooh, secrets run a different command instead of debug auto
wiring run debug container And whoa. This prints out a huge list. In fact, it's so
big. It's hard for me to see, Let me make the, my font smaller here. This Is the full
list of all of these services currently in the container. So the container is
basically a giant key value pair where each service has a unique name

That then points to that service object for. So for example, down here, let me scroll
up. There we go. You can see that there is a service whose machine name is twig, and
that is the twig service egg service. Knowing that the machine name of the twig
service is twig is not usually important, but it is useful to understand that each
service has a unique name. And you can see that inside this debug container command,
And really the container is better described as a big array of instructions on how to
instant services, if, and when something asks for them. So for example, the container
knows exactly how to instantiate this twig service. It knows that its class is twig
/environment. And even though you can't see it on this list, it knows the exact
arguments to pass to the constructor to twig environment. It knows the first
argument, second argument, and third argument to pass. It knows everything about how
to in create that object. And the moment that someone needs this service, the
container creates it and returns. It. That's what happens when we auto a wire. This
is actually a way to say, Hey container, can you please give me the HTTP client
service? If nothing in our code has asked for that service yet during this request,
the container will create it. But if something has already asked for this service
during the request, then the container will simply return the one it already created.
So a super nice feature of the container is we can just ask for the HDB client
service.

If we ask for the HDB client service in 10 different places, it will only create one
instance, need to explain that better. Anyways, debug container shows us all of these
services and the container, all the services that the container knows how to
instantiate, But debug on a wiring only shows us a fraction of those services. Why?
Well, it turns out that not all services are auto wearable. Many of these services
inside of here are just super low level services that exist just to help other
services do their job. And for those low level services, you'll probably never need
to use them directly. You could use them, but you'll probably never need to. And for
those low level services, those low level services, you cannot fetch them via auto
wiring. So lemme back up and explain how Symfony's auto wiring system works. It's
beautifully simple. As we've seen the container is really a key value pair where
every service has in a machine name that points to that service object. When Symfony
sees this HTTP client interface type hint For auto wiring, The full thanks to our use
statement. That's the full type that it sees when Symfony sees this type end in order
to figure out which service in the container to get to, to pass you. It simply looks
for a service whose ID exactly matches this string.

Now that probably won't let me show you. So if I scroll towards the top of this list,
You will actually see a service whose ID is Symfony contracts, HTP client HTP, client
interface. The vast majority of the services in the container use this like snake
case naming strategy. But if a service is meant for you to use in your code, Symfony
will add an additional service inside of here that matches the class or interface
name. So we type in HD client interface, Symfony looks in the container for a service
that matches this ID. It finds this service and it passes us to, it passes it to us.
Now you notice over here to the right, it says that this is an alias for a different
service ID. And that's just a, An alias is almost like a symbolic link. What it means
is that when somebody asks for the HTB client interface service, Symfony's actually
going to pass us whatever this service is here, Which is one of the services in this
list. We can do the same thing down here for the cache interface type end. So we're
type in with cache interface And you can find that service ID right here, and that is
an alias for the service ID called cache.app. So when we auto wire cache interface,
that's cache.app service is actually what's passed to us. So down here, you can see
that cache.app service is actually an instance of this traceable adapter.

All right. So I know that this was a little bit heavy, but here are the takeaways.
There are actually a ton of service objects floating around and they all live inside
something called the container, but only a small percentage of these are actually
useful to us. And the most useful ones are set ups that we can auto wire them. So
effectively when we run debug auto wiring, it just shows us the services from this
list that start with a class or interface name. Watch I run again, kind of see that
same list here in a more user friendly way. And you can even see in this list, which
makes a little bit more, more sense. Let me make my font bigger again. If I do debug
auto wiring and search for cache, you can see that the cache interface that we are
auto wiring into our controller, it actually shows you that that is an alias to the
service whose ID is cache.app. So again, it's not that important to understand that,
but now you can understand that this is the type you use, and this is actually the ID
of the service that you're going to get. If you use that type end.

Now, if you're wondering how we can use how we could use a non auto wire service in
our code, that's a great question. And we'll talk about that later, but that's pretty
rare. Most of the time you're going to be using one of the services from debug auto
wiring. All next. Let's talk about, um, different configuration locally versus
production. Let's talk about environments.


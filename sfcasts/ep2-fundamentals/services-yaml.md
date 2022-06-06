# Services Yaml

Coming soon...

When symfony first boots up, it needs to get the full list of all the services that
should be in the container. That includes the service ID, its class name, and all of
its constructive arguments. The first and biggest source of services are bundles. If
you run Debug contain `bin/console debug:container` The vast majority of these
services come from bundles. The second place that the container gets services from is
from, is our code and to learn about our services symfony reads, services.yaml, And
really important when symfony first starts parsing the first line of this file,
nothing in our source directory has been registered as a service in the container.
Adding our classes to the container is in fact, the job of this file, and the way it
does it is pretty amazing. Let's walk through it. Notice that the service config is
under eight. Service is key. And the first key under here is `_default`. This is a
sort of magic config key. This defines some default options that will be added to all
services that are registered in this file. So every service will automatically have
"autowire: true" and "autoconfigured: True".

What we did earlier at the bottom of this file Was actually to manually register a
service. We'll talk more about this part in a minute. The important thing is that our
service had a, we showed off that these service has a "bind" option and in fact,
services can have a bunch of different options, Including autowire and autoconfigure.
So it would be totally legal here for me to say, `autowire: true` and `autoconfigure:
true`. And that would work just fine because in fact, that is those two lines are not
needed, But thanks to the _default section, those are not needed. _default says
Unless it's been specifically overridden on a service set `autowire` and
`autoconfigured` to true for all services in this file. And what does autowire do?
Simple. It tells symfony's container Hey, please try to guess my constructor
arguments by looking at their type ends. We like that feature, which is why it's
automatically turned on for all of our services. The other option auto configure is
more subtle and we'll talk about it later. Okay. So by the time we get to this line
here,

We've established some default configuration, But we haven't actually registered any
services yet. That's the job of the next section. And it is the key to everything
This special syntax says, look inside the 'source directory and automatically
register' everything you see as a service pick, except for the stuff in these three,
Except for these three things. Yep. This is why immediately after we created the
mixrepository class, it was already in the container. And thanks to the _default
section, any services registered by this will automatically have 'autowire:true and
autoconfigure:True'. This mechanism is called auto service auto registration, but
remember every service in the container needs to have a unique ID. And if you look
back at Debug container, most of the service IDs are snake case. Let me make this
super tiny. So it's easier to see, for example, the twig service has the snake cache
twig ID, But if you scroll up to the top of this list, our mix repository ID is
actually the class name. Yep. When you use service auto registration, it uses the
class name as both the class and the service ID. This is done for simplicity, but
also for autowiring. Remember when we try to autowire mixrepository into our
controller or anywhere else to figure out which service to pass us symfony will look
for a service in a container whose ID exactly matches `App\Service\MixRepository`

So service auto registration, not only registers our classes as services, it does it
in a way that makes them autowireable into other services. That's awesome. Anyways,
after these, after this section here, we have now registered every class in source as
a service, except well, we don't want every class in source to be a service. There
are really two types of classes, 'service classes' that do work and 'model classes'
sometimes called DTOs, whose job is mostly just to hold the data In the next
tutorial, we'll create doctrine entity classes that will model the database. These
will live in the entity directory and they are not meant to be services. And so that
directory is ignored. So we register everything in the source directory as a service,
except for the things these three thinks. And actually this exclude key is not that
important. Heck you could delete it And everything would still work.

If you accidentally register something as a service that is not meant to be a
service, no big deal. Since you won't ever try to autowire and use that class like a
service symfony will realize that it's not being used and remove it from the
container. So big takeaway everything in the source directory is automatically
registered, registered as a service without us needing to think about it. So 99% of
the time, you never need to touch this file. But for mix repository, we have a non
autowire argument is debug. What we're doing down at the bottom of this file is
registering a new service whose ID and class Is `App\Service\MixRepository`. So this
will actually override the service that was created during service auto registration,
since both IDs will match app service mix repository. So we're defining a brand new
service down here, But then thanks to _defaults. It's automatically has `autowire:
true` and `autoconfigure: true`. And then we add the additional bind option that we
need to hint the argument. So the only thing you need to put at the bottom of this
file are services that need additional configuration to work. And actually there's a
cooler way to fix the non wire argument and I'll show show it to you next

But real quick. One last comment on this file services.yaml is loaded Via the exact
same system that loads all the files and `config\packages`. And in fact, there's no
technical difference between this file and say, `framework.yaml`

yep. If we wanted to, we could copy and delete the contents of services.yaml, Paste
them into `framework.yaml` And everything would work exactly the same, Except that we
would need to, you know, just crack these paths since we're one directory deeper
watch I'll approve this. This actually still works just fine. Let me undo that. There
we go. The only reason we have a service.Yaml file is just organization. It feels
good to have one file to configure your services. The truly important thing is that
all this config lives under the services key. In fact, near the top of this file,
notice there's an empty parameters key. And you might remember from earlier in
cache.yaml We created a parameter's key there to register a parameter. We could have,
it's really up to us to decide where, where we want to define this parameter. We can
do it in 'cache.yaml', or if we want to, we can copy this And move it over to
'services.yaml'

And `cache.yaml`, I'll also grab the, `when@dev` delete that and paste that into
`services.yaml` That on a technical level makes no difference. And our app still
works actually like this better services and parameters are a global idea in your
app. So it's nice to organize them all in one file. All right, next, the only reason
we've written any code at the bottom of our services.yaml file is to hit to the
container. What to pass to the non autowire is debug argument, but there's a more
automatic way to solve these problematic arguments.


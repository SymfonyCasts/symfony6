# Cache Config

Coming soon...

We're now using the HTTP client interface and cache interface, uh, services. Yay.
But, But we aren't actually responsible for creating these services. They're created
by something else That we're going to talk about in a few minutes. And then they're
just past to us. This is great because we can just use them without thinking about
anything. They come pre fully ready for us. But if something else is responsible for
instantiating, these service objects, how can we control them? Like for example, I
mentioned that the cache object stores, its files in the VAR cache via cache
directory. How could we tell the cache service to cache somewhere else? Introducing
bundle configuration, Go check out the config packages directory. This has a number
of different YAML files in it. All of which are loaded automatically by Symfony. When
it first boots up, let's look at one of them. And the entire purpose of all of these
files is to configure these services in the system. For example, open up twig.YAML.
And for now you can ignore this when at test, we're going to talk about that in a few
minutes. This file has a root key called twig. And the entire purpose of this file is
to control twig bundle.

And it's not the file name. That's important. Twig.YAML. I could rename this to Ryan
loves pizza.YAML, and it would work exactly the same. The key thing is that when
Symfony loads his file, it sees this root key twig and it says, oh, okay, I'm going
to pass. Whatever configuration is below this to twig bundle. Now, remember what do
bundles give us bundles, give us services. Thanks to this config. When twig bundle is
preparing these services for us, Symfony passes at this configuration and twig bundle
uses that to help instantiate its services. If we change this default path to
something like kernel that project or /views, The result is that the twig service
that renders templates would now be preconfigured to look in this new directory. The
point is these files, the purpose of these files are 100% about controlling these
services that the different bundles give us In every single one of these files
controls a different service. For example, framework Dimo. If you look at route key
framework, this is all configuration that is passed. Two framework bundle and helps
framework bundle, configure its services. Now, as I mentioned, the name file names
don't matter. They often match the root key framework, YAML framework, but not
always. There's also a cache.YAML, which actually is just more configuration for
framework bundle. It just happens to be that we put it in its own file, just so that
it's easier to configure the cache system.

All right. So one question you might be thinking is like, what configuration can you
put under here? Cuz you, you can't just Can't just make up keys. That would actually,
this would give you an air inside of Symfony. This is one of my favorite things about
Symfony configuration system. If you want to know what configuration you can pass to
twig bundle, there's two bin console commands to help you Bin console. The first is
debug config, and then you pass it that route key. What this is going to print out Is
all of your current configuration under the twig key, including any default values
that the bun that the bundle's adding. So you can see our default_path here, Which is
set to our templates directory

That is coming from our configuration file. This Nel project or presenting is just a
fancy way to point at the root of our project. So watch, if I change this to views,
Henry ran that command. You would see that it changes to views. Let me go ahead and
change that back. So debugging fig is going to show us all the configuration we can
have under the twig key plus whatever the current defaults are. So all this other
stuff is just other default values that the bundle's adding. So it's a really great
way to see what other things that you can do. What other things you can configure
about a bundle like for example, in the real world, you would read the documentation
to find out how to configure stuff. But just by looking in here, for example, it
looks like we could probably set some global variables in twig by using the globals
key. So that's the debug config command. There's another one that's very similar to
this instead of debug:config, it's config,:dump, debug config shows you your current
configuration dump shows you a giant tree of example, configuration, but this
includes everything that's possible. So here you can see globals and even has some
examples of some global values that you could configure.

So this is a great way to find out every single potential option that you can pass to
a bundle to help twig bundle configure its services PH. So I want to know how I can
configure the cache service us. So of course, in the real world, you can just Google,
how do I configure, uh, Symfony's um, cache service, but we can also just get it help
from these commands. So we kinda already noticed there's a cache.YAML service. So it
looks like the framework bundle is responsible for creating the cache service. And it
has a Subash key, which we can pass different values to, to help control it. All of
this right now is commented out. So to give more information about the framework
bundle, let's run bin console, config, dump framework Framework, bundle's the main
bundle inside of Symfony. So you can see that this dumps, wow, a lot of different
things that you can use to configure lots of different services that framework bundle
gives you. So to zoom in a little bit, let's actually pass at the argument's
framework, then cache that will show us just the cache sub key under framework

And cool. These still aren't always super understandable, but they're great starting
point, like for example, Why does the cache system store into the cache directory?
Because there's actually a directory key that defaults to kernel.cacher. That's a
fancy way of pointing at the VAR cache dev directory And then /pools /app. So /pools
/app. So this is actually the directory that's holding our current cache right now.

And then it has a number of other different configuration options below now the
option. All right. So here's my goal. Instead of cacheing things to the filesystem, I
want to change the cache system to cache somewhere else. So before we do that, go
into vinyl controller and just so we can see what change this is going to make, I'm
going to dump the cache service we've been using D D so far that's dump and die and
dump just dumps the variable, but doesn't kill the page. So check this out. I refresh
when you use dump, you won't actually see it on the page. It's hiding down here on
the web debug toolbar. But if you look here, there's some sort of traceable adapter,
but inside of there, there's something called a filesystem adapter. That's the cache
system saving to the filesystem.

Now, if we wanted to have that cache somewhere else, we can go into cache.YAML. And
the key we need to change is this app key. And the way you would know this is you'd
look at the documentation, it would help help you guide through this. And then over
here, we have a number of different adapters that we can use. So if we wanted to
store in Redis, we we'd use cache, adapter.Redis, just to make things really easy.
I'm going to use cache. Adapter array array is kind of a fake cache where it cachees
it like during the request, but then at the end of the request, it, it, it forgets
it. So it's kind of a fake cache, but it's enough so that we can see that changing
this key here will change the cache service itself. So just by changing this one
little thing here, watch what happens. And I refresh currently we have a filesystem
adapter. When I refresh now, the cache is an array adapter. And since the array
adapter forgets its cache. At the end of a request, you can see that every single
request now make makes an HT V HTP request. So if you're getting a little confused,
let me clarify here. The point of this chapter was not to teach you how to change
the, this specific key in the cache file.

Ultimately, if you need to configure something in Symfony, you are going to search
how to do it. And it's, documentation's going to tell you exactly like which key you
need to change. The big takeaway here is that the purpose purpose of these files, the
purpose of these files 100% is to configure these services in our application.
Anytime you change any key in any of these files, the end result of that is one of
these services Is now ha has been changed. It's still all about the services. All
right, next, sometimes you want some configuration to be Different based on whether
you're developing locally versus running on production. Symfony is a system for this
called environments. Let's learn about that.


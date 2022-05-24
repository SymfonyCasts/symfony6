# Prod Environment

Coming soon...

Okay. Our app is currently running in the dev environment. Let's try to switch to the
prod environment, which is what you would use on production. So temporarily change
this app on a score end to prod And refresh Whoa, the web debug toolbar is gone
That's because that entire bundle web profiler bundle is not enabled in the prod
environment. You also notice that the dump we have from our controller is dumping on
the top of the page. That's because the web profiler normally captures that and put
it down on the web deep Depot toolbar. Since that's not there anymore. It now dumps
on top The logger and there's lots of other differences. We know the logger will now
behave differently. Thanks to the configuration in monolog.YAML. And there are even
deeper differences. For example, Symfony cachees, a lot of files, but you don't
notice that in the dev environment because it's super smart and rebuilds its internal
cache. Whenever we change certain files, but in the pro environment that doesn't
happen. Check this out, find open up templates /base.H twig And change the title on
the page to stirred vinyl. When you go back over and refresh, look up here, no
change. The tweak templates themselves are cacheed again in the dead environment. It
rebuilds the cache for you, but in the PR environment, it doesn't. So how do you
clear the cache and the PR environment at your terminal run PHP, bin console

cache Cohen clear it notice says clearing the cache for the prod environment. So just
like how our application always runs in a specific environment. Even the console
commands run in a specific environment. And by default it reads that same app and
flag. So because we have app and prod here, cache clear, knew that it should run in
the prod environment and it cleared the cache for our prod environment. And thanks
this one refresh. Now the template was updated. Let me change that back on the
template to our cool name, mixed vinyl. All right, let's try something else right
now. If we open up config packages, cache.YAML, our cache service is using the array
adapter, which is a fake cache. It doesn't really cache anything. That's cool for
development, but will not work on production. So let's see if we can switch that back
to the filesystem adapter, but only for the pro environment. How well we know how to
do this down here, we can say is the, when at prod. And then we'll just repeat the
same keys we have here. So framework cache. So framework cache, and then app. And
then the adapter we want this time is called cache.adapter.filesystem.

Now it's going to be really easy to see if this works because we're still dumping the
cache service in our controller. And you can see right now it's an array adapter. If
I refresh surprise, it's still an array adapter. Why? Because we're in the pro
environment. And pretty much, anytime you make a change in the pro environment, you
need to rebuild your cache. So go back to your terminal and run bin console cache
clear again, And now got it. filesystem adapter, But I'm actually going to reverse
this config here. What I'm going to do is I'm going to copy cache, adapter.array. I'm
going to change it to filesystem. So by default, let's use the filesystem. Then at
the bottom, I'll say when at dev and here I'll use cache.adapter.array. Why am I
doing that? Well, that literally makes zero difference in the dev and prod
environments. But if we decide to start writing tests later, which run in the test
environment with this new config, the test environment, we use the same cache service
as production, which is probably more realistic and better for testing anyways, to
make sure this still works. Let's clear the cache one more time

Refresh and it does. We still have the filesystem adapter And now, and if we switch
back and.end to the dev environment and refresh As SW Debo toolbar back, and you can
see down here, we, once again are using the array adapter in the dev environment Per
<affirmative>. Perfect. So in reality, you probably won't ever switch to the broad
environment locally. It's just hard to work with. The pro environment is really meant
for production. All right, before we go on, I also want to go into vinyl controller
down and browse and let's take out that dump. Okay. Status, check. Everything in
Symfony is done by service. Two bundles, give us services and three, we can control
how those services are instantiated via the different bundle configuration in config
packages. Next let's go one important step further by creating our own service.


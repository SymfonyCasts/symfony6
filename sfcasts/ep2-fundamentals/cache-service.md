# Cache Service

Coming soon...

Right now when we refresh the brows page, these mixes are actually coming from an
HTTP request that we're making over to the GitHub API. So we make a request, the Git
up API, which fetches this file right here. And then response error. Two array turns
decodes.json into our mixes array and we render them in a template. So this file up
on GitHub is basically acting like our fake database. Now, one practical problem with
this is that every single page load is now making an HTTP request and HTTP requests
can be slow. And if I actually deployed this to production, we would pretty quickly,
our site would be so popular that we'd pretty quickly hit our GitHub API limit. And
this page would explode. So what if we cache the result of this? Like we make this
HTTP request, but then we cache the result for 10 minutes or one hour. So how do we
cache things in Symfony? Well, the answer is of course a service. So the real
question is, do we already have a cacheing service? And you know my answer, I don't
know. Let's go find out. So let's run bin console, debug auto wiring, and I will
search for cache. And yes, there are. In fact, there are several inside of here. So
you can see there's this thing called cache item, pool, interface, store interface.

Uh, not all these are cacheing things, but the cache I pool interface, cache
interface, and tag away cache interface are all different services that you can use
for cacheing. They all effectively do the same thing. The one you're going to want to
use is cache interface.

<affirmative>

So let's use it once again. We don't know how to cache, how to use this service, but
if we add a second argument called with cache interface, make sure you get the one
from Symfony contracts, cache, and I'll call it cache. And then to use the cache
service, it's kind of cool. I'm going to copy these two lines I had before and delete
them and replace them with mixes equals. And then you say cache Arrow. Get as if
you're going to fetch the key outta the cache, and then we'll just make up some cache
key here. How about mixes,_data? And then Symfony's cache object kind of works in a
unique way. You call cache, get, and you pass it this key. If that exists in cache,
if that results already exists in the cache, it but will be returned immediately. If
it doesn't exist in the cache yet, then it will call our second argument, which is a
function. And in here, our job is to return the data that should be cacheed. So I'm
going to paste in those two lines of code that we had before. Uh, HTB client is
undefined. I need to add a little used hang here to bring it into scope. There we go.
And instead of setting a mixes variable, we'll just return that

If we haven't used Symfony's Cing service before this might look a little strange at
first, but I love this. This is all that we need. The first time we refresh the page,
there won't be any mixes,_data in the cache yet. So we'll call our function. We
return the result and that will store that into the cache. The next time we refresh
the page, it will be in the cache and this will return immediately. So we don't need
any if statements or we don't need to check if anything is in the cache. All right.
So let's try this move over, refresh and beautiful. So this first request here, you
can see still made the HTP request.

And over here, you can see that there was three cache calls and one cache, right? So
I'm going to open this in a new tab. And this is really cool. This is the cache
section of WebView toolbar. And you can see here that there was one call to the cache
for mixes data. And there was one, right? And one miss, this was a cache miss. And so
it called or function. And it wrote that to the cache on the next refresh. Watch this
icon here, that disappears because there was no HTP request. And if I opened this
cache thing in the web D web to a bar, again, you can say there was one read, one hit
that hit from the cache that loaded from the cache. It didn't make an HTTP request.
All right. So how long is it cache for forever right now? Because we didn't add an
expiration. So to make this a little easier to use, let's add an expiration the way
you do that. If you look at the cache documentation is that this function receives a
cache item, interface argument, make sure, Hey, you hit tab to add that use statement
and we'll call that cache item.

And then what you can do with this cache item is you can say cache item->expires
after, and to make it easy, I'm just going to put 10 seconds or five seconds there
actually. So this says that the item will store in cache for five seconds.
Unfortunately, if we try this, the item that's already in the cache is scheduled is
set to never expire. So this will not work until we actually clear the cache. Where
is the cache being stored? That's a great question. And we're going to talk more
about that in a second, but the cache by default is actually stored in the VAR cache
directory, Along with a bunch of other cache files that help Symfony do its job. So
we could delete this, uh, directory manually to clear the cache, but Symfony has a
better way to clear things that of course is a bin console command. So if you're on
bin console, Symfony has a bunch of different, uh, kind of categories of cache, um,
called cache pools. If you're on bin console cache pool list, you'll get a list of
them. Most of these are meant for Symfony to use the cache pool that we are using is
called cache.app.

To clear that you can run bin console, cache

Pool clear and pass that. And that will clear that cache pool. This is not something
that you need to do very often, but that's how you do it. All right. So check this
out. When I refresh cache missed, you can see it made an HTP call, but if I refresh
again real fast, it's gone refresh again. It's back because the five seconds just
expired. Phew. So this is awesome. We have a service to make HTTP requests. We have a
service to cache stuff and Symfony, well, the bundles handle preparing and creating
those services for us with zero effort. We just use them. But I do have one question.
What if we need to control these services? Like how could we tell the cache service
that instead of saving things into the filesystem, in this directory that we want to
store things maybe in Redis, let's explore the idea of controlling our services
through configuration next.


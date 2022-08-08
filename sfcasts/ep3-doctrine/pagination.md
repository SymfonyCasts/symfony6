# Pagination

Coming soon...

Eventually this page is going to get super long. Imagine if we have a thousand mixes,
it will actually won't even load. Then what we probably need on this page is
pagination. Does doctrine have the ability to page eight results? It does though. I
usually install another library that adds even more features on top of it. Find your
terminal and run composer require Bab dev /pager Fonta bundle. And then also pager
Fonta /doctrine, ORM adapter. This installs a pager Fonta bundle, which is a wrapper
around a really nice library called pager Fonta pager. FAA can page eight, lots of
things like we can page eight doctrine, om, results, results from elastic search or
other things. So we've also installed it's doctrine, ORM adapter, which will give us
everything we need to page a, our doctrine results. In this case, when we run get
status, it added a bundle, but the recipe didn't really do anything. So here's how
this works. Open up src/Controller, vinyl controller, and find the brow action.
Instead of querying for all of the mixes like we're doing. Now, we're going to tell
the page of Fonta library, which page the user is currently on how many results to
show per page, and then it will query for the correct set of results for that page

To get this working, instead of calling find all ordered by votes and getting back
all the results. We need to be able to call a method on our repository that returns a
query builder. So open up source repository, vinyl, mixed repository, scroll down to
the find all order by votes. We're only using this method in this one place right
now. So I'm going to rename this too. Create ordered by votes query builder. And this
is going to return now a query builder, the one from doctrine all Ram, and now I'll
remove the PHP documentation on top. The only change we need to make down here is
instead of calling get query and get result, we're just literally going to return
query builder. Now over in a vinyl controller, we'll change this to query builder =
mixed repository-> create ordered by votes query builder, and then to initialize
pager font to two lines. First you're going to initialize a new adapter adapter = new
query adapter. This is from pager Fonta doctrine ORM. So this is an adapter that
works with doctrine. Om, and you pass this, the query builder. Then you'll create the
pager Fonta object

With pager. Fonta:four current page with max per page. That's a mouthful, but
anyways, this starts really nice that you pass it, whatever adapter you want. So pass
it adapter. And then the current page right now, I'm just going to hard code one and
then the max per page. So let's say nine since our mixes show three across. So now
that we have this pager Fonta object, we're actually going to pass that into the
template instead of our mixes. So I'll pass a new variable called pager set to pager
Fonta so here's the cool thing about this pager Fonta object. You can loop over it.
And as soon as you do it will create the correct query to get the results back. So in
templates /vinyl /browse H two mile twig, instead of four mix and mixes, it's four
mixin pager, that's it. It's still going to loop over result of vinyl mix objects.
When we reload, got it. It shows nine results. The results for page one, all right,
what we need now are links to the next page and maybe the previous page, and this
library can help with that too.

Back to your terminal. Run composer require pager Fonta /twig. One of the trickiest
things about the pager FAA libraries, instead of it being one giant library that has
everything you need. It's broken down into lots of smaller libraries. So if you want
the om adapter support, you need to install that like we did earlier. If you want the
twig support for adding the links, you need to install that what you do though. It's
pretty simple. Back in our template. Find the end four, right after it say {{ page,
your Fonta, and then pass it the page, your object, check this out. want to refresh
that adds links in the bottom. They're pretty ugly right now. And we'll fix that in a
second. And if you click the next link, you can see up here, you get question Mark
Page = two, though, the results don't actually change. We're still always on seeing
the same exact results. And that makes sense. Remember, back in our controller, we
hard coded the current pages page one. So even though we have question Mark Page =
two up here, pager Fonda still thinks we're on page one. What we need to do is read
this query parameter and pass it as this second argument. All right, no problem. How
do we read parameters? Well, that's information off of the request. So we're going to
want the request object

Before, before our optional argument, add a new argument type hinted with request the
one from HTP foundation and call it request. Now down here, instead of the one we can
say request->query. That's how you get query parameters,->get page. And we'll default
it to one. If it's missing, by the way, if you want to, you can also add like a curly
brace page up here. If you want to pager font will automatically put the page number
right there instead of setting it as a query parameter. All right, now I'm refresh
right now we have question Mark Page = two ask. You can see a different set of
results and down here it knows we're on page two. And then we can go over to page
three. Got it. Only last problem is that this is super ugly. So the bundle dis does
give you a way to control what the markup looks like when you call this function. And
fortunately it actually comes with a built in way to generate markup that's
bootstrap, CSS friendly. All we need to do is configure the bundle to tell it, to use
that markup in the config packages directory,

Oh, we need to configure the bundle, but this bundle didn't give us any new
configuration file and that's okay. Not all new bundles give you configuration files.
As soon as you need one, you can create one. Since this bundle's called BA dev Ponta,
I'm going to create a new file called Bab dev_Ponta, but as we.YAML, but as we
learned in the last tutorial, the name of these files aren't important. What's
important is the root key, which should be bad dev pager. Fonta now the way that then
the way that you change the, how the pagination like surrender is you add default
view twig and then default twig template. And then here you get those little at BBA
dev pager Fonta /Twitter boots, strap five.HWI. Now like any other configuration
we've done, there's no way that you would know that this is the correct
configuration. You need to, uh, use your, you need to check out the documentation.
All right? And now nothing changed. This is a little bug that you can sometimes run
into a Symfony when you create a new configuration file Symfony, didn't see this
configuration file.

If you modify an existing configuration file, it will see it. But in this case, it
didn't see the new configuration file. So it didn't need to know to rebuild its
cache. This is really the only case I can think of that. This happens in Symfony. And
honestly, this is probably a bug that will get fixed pretty soon. So in this case to
manually clear the cache, I'm going to run cache clear and oh, it explodes. You
probably saw why I love this air. There's no extension able to load the configuration
BA or dev under square pager. Fonta it's supposed to be Bab dev unders square page of
Fonta and now perfect. It's happy. And when we refresh it, sees it. Okay. We can
still do still, probably in a real project. want to add some extra CSS to make this
dark mode, but we've got it. Our team, we are basically done as a bonus. We're going
to refactor this pagination into a JavaScript powered forever scroll, except we're
going to do that without writing even a single line of JavaScript. That's next.


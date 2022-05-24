# Time Bundle

Coming soon...

On our site, you can create your own mixed vinyl, Or at least you will be able to
eventually right now, this bundles doesn't actually do anything. But then once users
create mixed tapes, you can go to browse to browse the existing ones. One thing that
might be useful to see on each individual mix-tape is when it was posted This page.
See what, however, this page is rendered. If you don't remember, you can actually use
a track down on the web Depot toolbar. This can show me that the controller for this
is Vinyl controller colon-colon browse. So over in src/Controller, vinyl controller
here is the brows action here. And what we do, I've updated the code a little bit
from episode one. What this does is it gets, uh, it calls this array getMixes, which
is just a little private function I created down here. This just creates a big array
of fake data that represents the mixes that we're going to run on the page.
Eventually, we will get this from a dynamic source, like a database, But anyways, you
can see that each mix does have a CreatedAt field. So we get these mixes up and
browse. We pass this as the mixes variable to browse.HTML.twig. So let's jump into
that template vinyl.browse.HTML.twig.

And down here, you can see, we use twigs at four loops to loop over mixes. Cool. So
let's just render the, created that date down here. So I'll put a pipe, another span
and I'll say {{ mix.And then the name of that key, which was created At Now. There's
just one problem CreatedAt, if you look back over here is a date, time object. You
can't simply print date, time objects. You will get a be old air that says that you
can't print date, time objects. They can't be converted to string. Fortunately, Twig
has a really nice date filter. We talked about filters briefly in the first episode,
filters are used by adding a pipe after it. And then the name of the filter. This
particular filter takes an argument, which is the format that you want to print the
date out in, For example, to keep things simple. I'll just put year-month-date. All
right now refresh. Okay. You can see when these were posted though, the format isn't
very attractive. We could do more work here to use a better format than Y-M-D, but it
would be way cooler. If we could print this out in the, a go format. That's where it
says, like posted three months ago or posted 10 minutes ago.

So the question is how can we convert a date time object into that nice ago format?
Well, that to me, sounds like work And work in Symfony is done by a service. So the
question is, is there a service in Symfony that can convert date, time objects to the
a go format? And the answer is no, But there is a third party bundle that can give us
that service Go to github.com/klab/kp time bundle This, bundle This bundle. If you
looked at its documentation, gives us a service that can do that conversion. So let's
get it installed. I'll go down to the composer require line, copy that spin over our
terminal and paste. Cool. You can see it grabbed the can the cany time bundle. It
also grabbed symfony/translation, which is a dependency of campy time bundle down
here. You can see that it configured two different recipes, So let's see what those
did. Awesome. So of course, install, anytime you install a third party package,
that's going to update your composer at Json and you're composed at lock files. And
you can also see the config/bundles.PHP was updated that's because we dist installed
a bundle. KnpTimeBbundle. And so it's recipe activated it automatically.

The recipe also looks like the translation recipe also looks like it added a
configuration file and a translations directory. We're not going to worry about that
stuff. What I really want to focus on here is we just added a new bundle to our
application, and I keep telling you that the purpose of a bundle is to give you more
services to give you more tools. So the question is what service or services did this
bundle? Just give us. And of course you can read the documentation and you should,
but I want to see if we configure this out by ourselves. So if you remember from the
last tutorial, there's a bin console command. You can run Php bin console to get a
list of all of these services in the system. It's a debug auto wiring. Ooh, For
example, if I search for logger, you can see there's one in here called log logger
interface. And for any of these services in this list, We learn in the first tutorial
that we can auto wire them into our controller by using its type end. So by using
this logger interface type end here, which is PSR log logger interface, Symfony knows
to pass us this service and this list. And then down here, we could call methods on
it like logger->info.

So in this case we just installed Knp time bundle. So let's search for time and Hey,
look at this, there's a new date, time format or service. That's probably what we
need. That's from the new bundle. Let's see if we configure out how to use this in
our controller. So the type in we need is date, time formatter. So let's go to vinyl
controller, go to browse And the, and I'll, And I'll add a new argument by the way,
the, the order of the arguments doesn't matter, except that I made the slug argument
optional. So you typically want your optional arguments at the end of your list. So
guys I'll add date time format. I'll hit tab to add the use statement on top. And if
we can call this anything we want, How about time format, Then what I'm going to do
is loop over the mixes. So I'll say four, each mixes as mix Actually, let's also
include the key. So key equal array mix. Then for each mix, I'm going to add a new,
ago key. So I'll say mixes key, ago equals. And this is where we're going to put that
new, ago formatter. How do we use this time? Formatter? I have no idea, but we have
its type end here. So I bet php storm will help us figure that out time format-> and
you can see the four different messages, uh, methods on it.

The one that we want is format diff and we give it the from time, which is going to
be our mix createdAt, and that's all we need to pass it. So just what I'm doing here
is I'm looping over these mixes down here, and I'm taking this createdAt key, which
is a date time object, giving it to the format diff function, and that will return
the string go format And see if this is working below. I'm just going to DD mixes.
All right, let's see if we're using this new service correctly, Spin over refresh and
let's open it up. Yes. Look at that. Ago seven months ago, ago, 18 days ago, it works
Sweet. So now that our mixes all have a new go field on it in browse.HTML twig, We
can remove the mix.createdAt and replace it with mix.ago And now Much better. So we
had a problem. We knew it needed to be solved by a service because services do work.
We didn't have a service that did that yet. So we went out on the internet and found
one, found a service Symfony itself has lots of different packages that give you many
services, but sometimes there'll be third party bundles like this one that are going
to be what you want.

And typically you can just search the internet for the problem you're trying to so
solve plus Symfony bundle to find it. Now, in the case of this specific bundle, in
addition to the nice date, time format or service that we used, that we're using in
our controller, this bundle also added a custom twig filter. Yes, that is something
that you can do. You can add custom functions and filters to twig. Check this out.
Another useful bin console command is bin console bin console, debug twig. This gives
you a list of all of the functions in twig, all of the filters in twig and all of the
tests in twig, which are kind of a fun feature. And even the one global variable that
we get inside of twig. Now, if you go up to filters, there's a new one called ago.
That was not there a second ago before we installed came time bundle, but it is there
now.

So all of the work we did in our controller is fine. We just don't need to do this
work. So I'm going to delete the four each. I'm going to remove the time form at our
service. And even though I don't need to, I'll even remove the use statement on top
to fully clean things back up now in brows that each amount twig we don't have in ago
field anymore, we still have a createdAt field though, which is that date time
object, instead of piping this to the date filter like we did before, we'll pipe it
to that, ago filter and that's all we need to do back over here on our site. When I
refresh, we get the exact same result, By the way, we won't do it in this tutorial,
but by the end, you'll be able to easily follow the documentation to create your own
custom twig functions and filters. All right, next, as we've seen, our app does not
have a database yet. We'll add that in the next tutorial, but to make things more
interesting, I want to make an API call from our code to a GitHub repository and use
that as our mix tape data source.


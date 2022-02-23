# Services

Coming soon...

I mentioned earlier that I see Symfony as two pieces. The first is the route
controller response system. It's dead simple and well, you're already an expert on
it. The second half of Symfony is all about the many useful objects that are floating
around. For example, when we render a template, what we are actually doing is taking
advantage of a twig object and asking it to render a template. There are, there's
also a logger object, a cache object in many, many more like a database connection
object, an object that helps make API requests in on and on. And when you install
more packages, those give you more useful objects. And the truth is that every single
single thing that Symfony does is actually done by one of these useful objects. Heck
there is even a router object that's responsible for matching which route matching
the correct route for the given page and generating you URL from routes in the
Symfony world, and really the object oriented programming world in general, these
useful objects have a special name services, but don't let that word confuse you when
you hear service. Just think that's an object that does some work like a logger or
day object

Services are basically since, since services do work, they're basically tools. And
the second half of Symfony is all about discovering which services are available and
how to use 'em in on controller. I want to log a message. Maybe some debugging
message. Since logging a message is work. It's done by a service. Does our app
already have a logger service? And if so, how do I get it from in here to find out,
move over dear terminal And run another bin console command PHP bin console, debug
auto wiring Say hello to one of the most powerful bin console commands. I love this
thing. This lists all of the services that exist in our app. Okay. It's actually not
the full list, but it's these services that you're most likely to use. And even
though our app is small, there's a lot of stuff in here. There's a filesystem service
apparently, And down here, a cache service There's even a twig service. Is there a
service for logging? You can look in this list or you can rerun this. I'll just
search for the word log. This tells us that there You can ignore everything for now.
You can ignore everything except for this first line. This tells us that there is a
logger service and that it implements this PSR log logger interface. How does knowing
that help us?

Because it, if you want a service, you ask for it by using its type hint, It's called
auto wiring. Here's how to auto wire from inside a controller. So you see this logger
interface. We're gonna head over to our controller and add an a second argument.
Actually, it doesn't matter if this is the first argument or the second argument, the
order of these arguments don't matter. What matters is the type in is logger
interface. I'll hit tab to auto, complete that and add the use statement on top. Now
the argument can be called anything like logger. So when Symfony sees this type end,
it's going to look inside of its debug auto wiring list. And because there's a match,
it's going to pass us the logger service. So we now know two different types of
arguments that you can, that you are allowed to have in controller. You can either
have an argument whose name matches a wild card or whose type matches one of the
services in our system. Now below we can use it. So I'll say logger arrow, and we
don't know what methods are on this, but because we know this is gonna be a logger
interface, peach storm gives us the, a list of all the different methods on that
object without having to do any other work, I'm gonna log something at an info
priority level. And we'll just say, returning API response for song and then.id.

Cool. Oh, and actually you can something even cooler than can continuating the idea
in here. This is specific to the logger service. So you can say curly song and curly,
and the logger actually takes a second argument, which is just extra information you
want to attach to this log message. You can actually pass this song in like that in a
second. You're gonna see that the log message is gonna kind of contain these details
with the ID kind of swapped in right here. Anyways, we are on our API endpoint. So
let's go over and refresh this. And, Uh, no air did it work. Where did this log to
The logger service that we're using is provided by a library. We installed earlier
Called monolog. We actually installed it as part of the debug pack. And you can
control its configuration via config packages, monolog dot yamal, And it turns out,
But we'll focus more on config in the next tutorial, because one way you can always
see the log messages for request. If I go to the homepage, You can see the log
messages made during your request are in that request. Profiler. Remember that's
where you click any of these web Debu, two of our links down here, and then you can
click logs. And we're now seeing the log messages from just that homepage request.

But API N points don't have a web debug toolbar. So are we stuck? Nope. Refresh this
page one more time and copy the URL and head to slash_profiler. This is kind of a
back door into the profiler system and what it does. It actually lists all of the
last 10 requests made into our system. Check out the second to the top here. That's
the request we just made a minute ago to our API endpoint. If you click this token
over here. Yeah. You're looking at the profiler for that API endpoint Over in the log
section. Yes, there it is returning a API response for song five and there's even the
extra context you can see that we passed there. Let's look at one other example of
services, go back to vinyl controller. The render method is really just a shortcut to
fetch the twig service, Call some method on that service to render the template and
put the final HTML string into a response object. And it's a great shortcut method
and you should use it. But as a challenge, could we render a template without using
that method? Sure. Step one. Find the twig service. So I'll do our trick again. Debug
auto wiring search for twig and yeah, apparently the type end is twig /environment.

Cool. Let's go back to our method and now we can type in via and min, uh, tab to auto
complete that. So it adds the U statement and then we can call the argument. Anything
I'll say twig Below instead of using render, I'll say HTML = and then TWI big arrow.
And once again, I don't know what methods are inside this class, inside this class,
but thanks to the type in it. Auto completes all the methods on here and you don't
have to go far that render method looks like the right method. And it's first
argument is the name, the string name of the template you wanna render and context is
the variable. So has the same arguments that we were already passing. See, this is
working below. Let's just DD that HTML. All right, try it. Let's head over to the
homepage. And yeah, We just rendered a template manually. We're awesome to return a
response. We can just return new response and put the HTL inside of that. And now our
page works again. This was a cool exercise. And now you know that when you see this
arrow, right, What that really does is uses the twig service to render a template,

But in a real app, there's no reason to do all this extra work. So I'm going to
revert this to using this error render, and then we don't need to auto wire that
argument anymore. And we can even clean up the use statement. The big takeaways are
this one Symfony is packed full of useful objects called services Services are tools.
Second, all work in Symfony is done by services. Even, Even things like routing. And
third, we can use services directly By auto wiring them by auto wiring them to help
us get our work done in the next tutorial. In this series, we'll dive deeper into
this very important concept. Okay. We are getting near the end of this tutorial, but
I wanna do one more big, awesome, amazing thing. Next let's learn about Webpac
Encore, Which is going to help us write truly professional CSS and JavaScript over
these last few chapters. We're going to bring our site to life and even make it as
responsive as a single page application.


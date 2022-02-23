# Profiler

Coming soon...

Time to install our second package and this one is fun, but let's commit our changes
before we do that. Makes checking out any recipe, Any recipes from those new
packages, a lot easier, our, a good status. I'll add everything That looks fine and
we'll commit Beautiful. Now let's install one of my favorite things run composer
require debug. So yes, that is another flex alias. And apparently it's an alias for
Symfony debug pack. And we know what a pack is. It's a collection of it's a
collection of libraries. So instead of just adding this one line to our composer,
that JSON file it. If we check, It looks like it added one new package up here under
the required section. This is for a logging library and All the way at the bottom, it
added a new required dev section with three other libraries. The difference between
require and required dev isn't too important. All of these packages were downloaded
into our app, But as a best practice, if you install a library, that's only meant for
local development, you should put it into required. Dev Let's see, back at the
terminal, this also installed three recipes. Ooh, let's see what those did. I'll
clear the screen and run kit status.

Awesome. So this is kind of familiar. It modified config /bundles dot PHP And added
three and activated three new bundles Then get bundles are like Symfony plugins,
which add more features to our site. And then it also added a number of configuration
files in the config packages directory. We're gonna talk more about the purpose of
these files in the future tutorial, but again, on a high level, the, the, these
configuration files control behavior of those bundles. All right. So what did this
all do? Clearly we ran composer required debug. So it installed some debugging tools.
Well, to find out, find your browser and refresh the homepage. Holy cats, Batman,
it's the web debug toolbar. This is debugging madness. There's two bars full of good
information. On the left side, you can see the controller that was called along with
the HTB status. And it has other information like the amount of time the page took
the memory it used and also information about how many templates were rendered
through twig. This is the cute little twig icon and how long those templates took.
And we have more information over here on the right side

About the Symfony, uh, local server that's running and also, uh, our version
information. But what's really cool about this is that you can click any of these
icons down here to jump into the profiler. This is like the web debug toolbar
information gone crazy. This is full of information about that request, like re
request and response data, all of the law messages that happened during that request,
uh, information about the routes and the route that was matched. Twig shows you which
templates were rendered and how many times they were rendered Configuration
information down here. And my most favorite thing of all time per performance, an
awesome timeline of what happened behind the scenes. This is so great for two
reasons. First, obviously this can help you find performance issues. It can show you
which parts of your code are slow. So for example, you can see how our controller, he
took 20.4 milliseconds within controller rendering. Our homepage template took 3.9
milliseconds and our base H twig template took 2.8 seconds milliseconds.

The second reason that this is really cool. If you set this threshold down to zero
that tell else the profiler previously, it was hiding anything that took. It was only
showing things that took more than one millisecond. Now it's showing everything. You
don't have to worry about the vast majority of the stuff right now, but this really
uncovers the hidden layers in Symfony, the hidden things that happened before your
controller and after your controller, we have a, a deep dive tutorial on Symfony. If
you wanna learn more about this and how Symfony works behind the scenes And this
profiler area and web debug toolbar is also going to grow with our application in the
future tutorial. When we install a library to talk in the database, there's gonna be
a section here that shows all the database queries that a page made and how long each
of those took that's so useful. All right, so that debug pack installed the web DBU
toolbar. It also installed a logging library that we'll use later, and it gave us two
fantastic little debug functions that I really wanna show you right now. So head over
to vinyl controller, and let's say, we're doing some developing and we wanna see what
this TrackX variable looks like. It's pretty obvious in this case, but sometimes

Symfony will hand you an object and you wanna just see what that object looks like to
do that we can use D D and then tracks DD stands for dump and die. So quite
literally, when we refresh, it's going to dump that, and then it's gonna kill the
page. And this is a lot more powerful than VAR dump. It allows you to expand things
and see deep data really, really easily. Instead of DD, you can also use dump that
will dump the variable, but not die. The one kind of thing to know about that is that
you might expect it to show up right in the middle of the page, but instead it
actually shows down here on the web Depot tool bar under this whole target icon. And
if that's a little bit too small, you can click that and see it in bigger format
inside of your profiler. And you can also do the exact same thing inside of twig. So
I'll take this it out of my controller, and then we're gonna go into our template.
And right before the UL I'll use dump tracks. And as you could probably guess that
looks the exact same way. The only difference is that when you use dump and twig, it
actually does dump it right in the middle of the page.

And maybe even more useful in twig only, you can't do this in normal P in when you
use it PHP, but when you use dump and twig, you can pass no arguments. This is going
to dump all of the variables we have access to. So there's our title, variable, our
tracks, variable and surprise. There's actually a third variable called app, which is
an instance of this app variable object. This is a global variable you have in all
templates, and it gives you access to things, the things like the session and other
information. And we just discovered it was there by being curious with the dump
function. All right. So now that we've got these awesome debugging tools in place,
Let's turn to our next job, which is to make this site less ugly. Let's bring in some
CSS and a proper layout to really bring our site to life.


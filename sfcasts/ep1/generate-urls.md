# Generate Urls

Coming soon...

There are two different ways that we can interact with our new a app. The first is
via the web server. Like right here, I go to web browser. I go to the URL And behind
the scenes, we know this is actually executing this public /index PHP file, which
boots up Symfony executes a routing, executes our controller and takes care of
everything. The second way that we can interact with our application is via a command
line tool called bin console at your terminal type PHP bin /console two, see a bunch
of different commands. We can run tons of Debu commands. Eventually we'll have code
generation commands in here. Commands ahead is setting secrets, all kinds of good
stuff that we're going to discover little by little. Now there's nothing special
about this bin console file. There's literally a bin directory with a console file
inside. You'll probably never need to open this file or think about it. But when you
run bin console, you are just executing this file, which boots up Symfony's command
line tools, And most systems you should be able to just run bin /console That works
as well. You might also see me sometimes run Symfony console. Remember Symfony is the
tool we that we use to, um, start our web server.

When we run Symfony console, that's just a shortcut to run bin /console. We'll talk
more about that in future tutorials. So the one command that I wanna show you right
now, that's inside of here is debug router. This is awesome because it shows you
every single route in our system. We can see our two routes here /and /brows /slug,
as well as a bunch of routes, a bunch of other routes. These come from the web debug
toolbar and the profiler tools that show up at the bottom of our page. And they'll
only be there when we're locally developing. All right, back on our site. At the top
of the page, we have two nonfunctional links to the homepage and to the brows page,
let's hook these up open templates /base HTML twig, And I'm gonna search for a tags.
Here we go. It would be really easy to get this working by just saying = slash. But
instead, whenever we link to a page in Symfony, we are going to ask the routing
system to generate a URL for us. Like we'll say, please generate The URL to the
homepage controller or the browse controller. Then if we ever change the URL to
these,

To these routes, all our links would instantly update. So let's start with the
homepage. How do we ask Symfony to generate a URL to this route? The answer is by
using its route name. Yep. Every route has an internal name. You can see it back at
Debu router. First, he here is name and surprise. Our two routes have names. Where
did those come from? By default Symfony generates a name for you, which is fine. It's
not used at all until you start trying to generate a URL to that route. And as soon
as you do need to generate a URL to a route, I highly recommend taking control of
this name, just to make sure it never accidentally changes To do this, find your
route. And we're gonna add an argument here. Name set to this could be anything, but
I like using app an app_prefix, and then some short but descriptive name. So app
under score homepage, By the way, these attributes, this route attribute. These are,
this is an actual physical PHP class. And if you hold commander control, you can open
it and look inside and you can see the construct method shows all of the different
options you can pass to of this. So there's a name option, and we're using PHPs named
argument syntax to pass this argument, this value to that name argument. It's a
really great way to learn more about how a specific attribute works

Anyways. Now that we've given this name, if we go back to our terminal and run diva
route yes, app_homepage, the route name is now app_homepage, New name. All right,
let's copy this. And then it back over to base dot H twig to generate URL inside of
twig. We say curly curly, because we're going to print something and then use a
special path function from twig and pass in the route name. That's it. Now refresh. I
can click this link up here and it works. All right. Let's link to the brows page. So
we know step one, we need to give our route a name. So name, colon. And how about
app_browse? I like these route names to be as short as they can while still making
sure that they will be unique in your applic. Yeah, we'll copy that. And down here a
little bit, here we go. Browse mixes. We'll change that ATF two curly curly path, and
then app_browse. And now That link works. Oh, but on this page, we have some quick
links To go to the browse page for a specific genre. And these do not work yet. This
is interesting.

We want to, we wanna generate a URL like before, but this time we want to pass
something to this slug wild card, Open brows dot H twig. Here's how you do that.
First. Part's the same. I'll say curly curly path. And then the name of our route,
just app_brows. Now, if we just stop there, it would only generate /brows to pass
values to the wild cards. There's a second argument here, which is an array, an
associative array of data that you can pass in inside of twig, much like JavaScript
to do an associative array. You do open and curly close curly. I'm you're going to
Hit enter to break this on multiple lines, just to keep things a little bit shorter.
So inside of here, we'll pass slug. And since this is the pop genre, I'll say pop Or
let's repeat this a couple more times. Curly curly path app under square brows, pass
curly braces for an associative Ray or a hash slug set to rock. And then one more
time down here. Path app under square browse, Passing these slug wild cards set to
heavy dash metal. All right, let's see how it works. Refresh. Oh, an air variable
rock does not exist. I bet you saw me do that. I forgot my quotes here. So this looks
like a variable and twig.

All right, try it again. There we go. If we click those awesome, they all work. All
right. Next. We've created two HTL pages. Now let's see what it looks like to create
a JSON API endpoint.


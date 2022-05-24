# Bundles

Coming soon...

Welcome back people. This is episode two of our Symfony six tutorial series. The one
where we seriously unlock your potential to do anything you want. That's because in
this course, we're diving into the fundamentals behind everything in Symfony. We're
talking about services, bundles, configuration, environments, environment variables,
everything that makes Symfony tick As always to get the most out of the tutorial. I
Warmly invite you to download the course code and to code along with me to do that,
you can download the course code from this page. When you unzip it, you'll have a
start directory with the same code that you see here. You can follow this nifty
readme.md file for all these setup instructions. The last step will be to open a
terminal.

These are available.

Move into the project and run Symfony Serve-D to start a local web server At 1 27, 0
8 one,:8,000. I'm going to cheat and click that link to see our site mixed vinyl, Our
new startup idea, Where users can build their own custom mix tape, except that We
deliver it to you on hipster vinyl. All right. So In the previous tutorial, I
mentioned briefly that everything in Symfony is done by a service and that word
service is a fancy word for a simple concept. A service is an object that does work.
For example, in the first tutorial in src/Controller song controller, we leveraged
the Symfony's logger service to log a message. We also, even though we don't have the
code anymore in vinyl controller, we used the twig service to render a twig templates

<affirmative>.

So a service is just an object that does work and every bit of work that's done in
Symfony is done by a service. Even the code that figures out what route matches the
current URL That's done by a service called the router service. Okay, cool. So next
question is where do these services come from? The answer to that is bundles open up
config/bundles.PHP. This is not a file that you need to look at or worry about much,
but this is where your bundles are. Activated. Bundles are Symfony plugins. They're
just PHP code, but they hook into Symfony. And thanks to the recipe system. When we
install a new bundle or application, the recipe automatically adds it to this file,
which is why we have already nine lines in here, nine bundles, eight bundles. So a
bundle is a Symfony plugin, and a bundle can give you several things, but they pretty
much exist for one reason to give you services. For example, this twig bundle up here
that gives us the twig service. If we remove that line twig, the twig service would
no longer exist. And our application would explode since we are actually rendering
templates

That render line would no longer work. And the monolog bundle is what gives us the
logger service that we're using in song controller. So by adding more bundles into
our application, we are getting more services and services are tools, need a new
service to solve a new problem, Need more services, install more bundles. In fact,
let's do that next. Let's install a new bundle that gives us a new service to solve a
new problem.


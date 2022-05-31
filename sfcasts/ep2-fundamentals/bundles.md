# Bundles!

Hey friends! Welcome back to Episode 2 of our Symfony 6 tutorial series. The is
the one where we *seriously* unlock your potential to do *anything* you want. That's
because, in this course, we're diving into the fundamentals behind *everything* in
Symfony. We're talking about services, bundles, configuration, environments,
environment variables - everything that makes Symfony *tick*.

## Site Setup!

To get the *most* fundamentals out of this fundamentals tutorial, I warmly invite
you to download the course code from this page and code along with me. It'll be fun!
After you unzip the file, you'll find a `start/` directory with the same code that
you see here. Follow our hand-crafted, locally-sourced `README.md` file for all of
the setup instructions. The *last* step will be to open a terminal, move into the
project and run

```terminal
symfony serve -d
```

to start a local web server at `https://127.0.0.1:8000`. I'll cheat and click that
link to see our site. It is... Mixed Vinyl! Our new startup idea, where users can
build their own custom "mixtape" - I'm thinking MMMBop followed by some Spice Girls...
except that we deliver it straight to your door on a freshly pressed vinyl record.
We even throw in that musty old record collection smell for free!

## Services: Services Everywhere

In the previous tutorial, I mentioned we talked briefly about how *everything* in
Symfony is actually done by a *service*. And that the word "service" is a fancy term
for a simple concept: a service is an object that does work.

For example, in `src/Controller/SongController.php`, we leveraged Symfony's Logger
service to log a message. And, though we don't have the code in `VinylController`
anymore, we used the Twig service to render a Twig template.

So a service is just an object that does work... and *every* bit of work that's done
in Symfony is done by a service. Heck, even the core code that figures which route
matches the current URL is a service, called the "router" service.

## Hello Bundle

So the next question is: where do these services come from? The answer to that is
*bundles*. Open up `config/bundles.php`. This isn't a file that you need to look at
or worry about much, but this is where your bundles are *activated*.

Very simply: bundles are Symfony plugins. They're just PHP code... but they hook
*into* Symfony. And thanks to the recipe system, when we install a new bundle, it
is *automatically* added to this file, which is why we already have 8 bundles
here. When we started our app, we only had 1!

So a bundle is a Symfony plugin. And bundles can give us several things... but
they pretty much exist for one reason: to give us *services*. For example, this
TwigBundle up here gives us the Twig *service*. If we removed this line, the Twig
service would no longer exist and our application would *explode*... since we *are*
actually rendering templates. This `render()` line would no longer work. And
MonologBundle is what gives us the Logger service that we're using in
`SongController`.

So by adding more bundles into our application, we're getting more *services*, and
services are tools! Need more services? Install more bundles!

Next... let's do that! Let's Install a *new* bundle that gives us a *new* service
to solve a *new* problem.

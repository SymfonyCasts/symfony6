# Bundles

Hey friends! Welcome back! This is Episode 2 of our Symfony 6 tutorial series. The one where we *seriously* unlock your potential to do *anything* you want. That's because, in this course, we're diving into the fundamentals behind *everything* in Symfony. We're talking about services, bundles, configuration, environments, environment variables - everything that makes Symfony tick. As always, to get the *most* out of the tutorial, I invite you to download the course code and code along with me. You can download the course code from this page. When you unzip it, you'll have a start directory with the same code that you see here. You can follow this nifty `readme.md` file for all of the setup instructions. The *last* step will be to open a terminal, move into the project and run

```terminal
symfony serve -d
```

to start a local web server at `https://127.0.0.1:8000`. I'm going to cheat and click that link to see our site, Mixed Vinyl - our new startup idea, where users can build their own custom mixtape, except that we deliver it straight to your door on a freshly pressed vinyl record. We even throw in that musty old record collection smell for free!

In the previous tutorial, I mentioned briefly that everything in Symfony is done by a *service*. The word "service" is a fancy word for a simple concept. A service is an object that does work. For example, earlier, in `src/Controller/SongController.php`, we leveraged Symfony's Logger service to log a message. And even though we don't have the code in `VinylController` anymore, we used the Twig service to render a Twig template. So a service is just an object that does work, and every bit of work that's done in Symfony is done by a service. *Even* the code that figures out what route matches the current URL. That's done by a service called the Router service.

So the next question is: Where do these services come from? The answer to that is *bundles*. Open up `config/bundles.php`. This isn't a file that you need to look at or worry about much, but this is where your bundles are *activated*. Bundles are Symfony plugins. They're just PHP code, but they hook into Symfony. And thanks to the recipe system. when we install a new bundle or application, the recipe *automatically* adds it to this file, which is why we already have eight lines in here, or eight *bundles*. So a bundle is a Symfony plugin, and a bundle can give you several things, but they pretty much exist for one reason: To give you *services*. For example, this `TwigBundle` up here gives us the Twig *service*. If we remove this line, the Twig service would no longer exist and our application would *explode*, since we *are* actually rendering templates. This `render` line would no longer work. And the `MonologBundle` is what gives us the Logger service that we're using in `SongController`.

So by adding more bundles into our application, we're getting more *services*, and services are tools. Need more services? Install more bundles!

Next, let's install a *new* bundle that gives us a *new* service to solve a *new* problem.

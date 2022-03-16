# The Twig Service & Profiler for API Requests

Since this page just loaded without an error, we *think* that we just successfully
logged a message via the logger service. But... where do log messages go? How
can we check?

The logger service is provided by a library that we installed earlier called monolog.
It was part of the debug pack. And you can control its configuration via the
`config/packages/monolog.yaml` file, including *where* log messages are logged to,
like which file. We'll focus more on config in the next tutorial.

## The Profiler for API Requests

But one way that you can *always* see the log messages for a request is via the
profiler! This is super handy. Go to the homepage, click any link on the web debug
toolbar... and then go to the Logs section. We're now seeing *all* the log messages
that were made during that last request.

Great! Except that... our log messages are made on an API endpoint... and API
endpoints do *not* have a web debug toolbar we can click! So are we stuck? Nope!
Refresh this page one more time... then manually go to `/_profiler`. This is... kind
of a secret door into the profiler system... and this page shows the last *ten*
requests made into our system. The second to the top is the API request we just
made. Click the little token icon to see... yea! We're looking at the profiler for
that API endpoint! Over in the Logs section... there it is!

> Returning API response for song 5

... and you can even see the extra info we passed there.

## Rendering a Twig Template Manually

Ok, services are *so* important that... I want to do one more quick example.
Go back to `VinylController`. The `render()` method is *really* just a shortcut to
fetch the "twig" service, call some method on that service to render the template...
and then put the final HTML string into a `Response` object. It's a *great* shortcut
and you *should* use it.

But! As a challenge, could we render a template *without* using that method?
Of course! Let's try it.

Step one: find the service that does the work you need to do. So, we need to find
the Twig service. Let's do our trick again:

```terminal
php bin/console debug:autowiring twig
```

And... yes! Apparently the type-hint we need to use is `Twig\Environment`.

Ok! Go back to our method, add an argument, type `Environment`, and hit tab to
auto-complete that so PhpStorm adds the `use` statement. Let's call it `$twig`.

Below, instead of using render, let's say `$html =` and then `$twig->`. Like
with the logger, we don't need to know what methods this class has because, thanks
to the type-hint, PhpStorm can *tell* us all the methods. That `render()` method
looks like it's probably what we want. The first argument is the string name of the
template you want to render and context holds the variables. So... it has the same
arguments that we were already passing.

To see if this is working, below, `dd($html)`.

Testing time! Head to the homepage... and yes! We just rendered a template manually!
We're awesome! Let's finish this by returning a response: `return new Response($html)`.

And now... the page works! And we understand that the *true* way to render a template
is via the Twig service.

But in a real app, there's no reason to do all this extra work. So I'm going to
revert this... and go back to using `render()`. And... then we don't need to
autowire that argument anymore... and we can even clean up the `use` statement.

Here are the three big takeaway. First, Symfony is *packed* full of objects that
do work... which we call services. Services are tools. Second, *all* work in Symfony
is done by a service... even things like routing. And third, *we* can use services
to help us get *our* work done by autowiring them.

In the next tutorial in this series, we'll dive deeper into this very important
concept.

Before we finish the tutorial, I *really* really want to talk about one more big
awesome, amazing thing: Webpack Encore, the *key* to writing professional CSS and
JavaScript. Over these last few chapters, we're going to bring our site to life and
even make it as responsive as a single page application.

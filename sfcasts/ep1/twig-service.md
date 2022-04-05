# The Twig Service & Profiler for API Requests

Since this page just loaded without an error, we *think* that we just successfully
logged a message via the logger service. But... where do log messages go? How
can we check?

The logger service is provided by a library that we installed earlier called monolog.
It was part of the debug-pack. And you can control its configuration inside the
`config/packages/monolog.yaml` file, including *where* log messages are logged to,
like which file. We'll focus more on config in the next tutorial.

## The Profiler for API Requests

But one way that you can *always* see the log messages for a request is via the
profiler! This is super handy. Go to the homepage, click any link on the web debug
toolbar... and then go to the Logs section. We're now seeing *all* the log messages
that were made only during that *last* request to the homepage.

Great! Except that... our log message is made on an API endpoint... and API
endpoints don't have a web debug toolbar we can click! Are we stuck? Nope!
Refresh this page one more time... then manually go to `/_profiler`. This is... kind
of a secret door into the profiler system... and this page shows the last *ten*
requests made into our system. The second to the top is the API request we just
made. Click the little token link to see... yea! We're looking at the profiler for
that API request! Over in the Logs section... there it is!

> Returning API response for song 5

... and you can even see the extra info we passed.

## Rendering a Twig Template Manually

Ok, services are *so* important that... I want to do one more quick example.
Go back to `VinylController`. The `render()` method is *really* just a shortcut to
fetch the "twig" service, call some method on that object to render the template...
and then put the final HTML string into a `Response` object. It's a *great* shortcut
and you *should* use it.

But! As a challenge, could we render a template *without* using that method?
Of course! Let's do it.

Step one: find the service that does the work you need to do. So, we need to find
the Twig service. Let's do our trick again:

```terminal
php bin/console debug:autowiring twig
```

And... yes! Apparently the type-hint we need to use is `Twig\Environment`.

Ok! Go back to our method, add an argument, type `Environment`, and hit tab to
auto-complete that so PhpStorm adds the `use` statement. Let's call it `$twig`.

Below, instead of using `render`, let's say `$html =` and then `$twig->`. Like
with the logger, we don't need to know what methods this class has, because, thanks
to the type-hint, PhpStorm can *tell* us all the methods. That `render()` method
looks like it's probably what we want. The first argument is the string name of the
template to render and the `$context` argument holds the variables. So... it has
the same arguments that we were already passing.

To see if this is working, `dd($html)`.

[[[ code('9dd37843fc') ]]]

Testing time! Head to the homepage... and yes! We just rendered a template manually!
Seriously awesome! And we can finish this page by wrapping that in a response:
`return new Response($html)`.

[[[ code('a862fa6787') ]]]

And now... the page works! And we understand that the *true* way to render a template
is via the Twig service. Someday, you'll find yourself in a situation where you
need to render a template but you are *not* in a controller... and so you do
*not* have the `$this->render()` shortcut method. Knowing that there's a Twig
service you can fetch will be the *key* to solving that problem. More on that in
the next tutorial.

But in a real app, in a controller, there's no reason to do all this extra work.
So I'm going to revert this... and go back to using `render()`. And... then we
don't need to autowire that argument anymore... and we can even clean up the
`use` statement.

Here are the three big, gigantic, important takeaways. First, Symfony is *packed*
full of objects that do work... which we call services. Services are tools. Second,
*all* work in Symfony is done by a service... even things like routing. And third,
*we* can use services to help us get *our* work done by autowiring them.

In the next tutorial in this series, we'll dive deeper into this very important
concept.

But before we finish *this* tutorial, I *really* really want to talk about one more
big awesome, amazing thing: Webpack Encore, the *key* to writing professional CSS
and JavaScript. Over these last few chapters, we're going to bring our site to life
and even make it as responsive as a single page application.

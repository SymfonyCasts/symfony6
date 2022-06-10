# The HTTP Client Service

We don't have a database yet... and we'll save that for a *future* tutorial. But
to make things a bit more fun, I've created a GitHub repository -
https://github.com/SymfonyCasts/vinyl-mixes - with a `mixes.json` file that holds
a *fake* database of vinyl mixes. Let's make an HTTP request from our Symfony
application *to* this file and use *that* as our temporary database.

So... how *can* we make HTTP requests in Symfony? Well, making an HTTP request is
*work*, and - say it with me now - "Work is done by a service". So the next question
is: Is there already a service in our application that can make HTTP requests?

Let's find out! Spin over to your terminal and run:

```terminal
php bin/console debug:autowiring http
```

to search the services for "http". We *do* get a bunch of results, but... nothing
that looks like a HTTP client. And, that's correct. There is *not* currently any
service in our application that can make HTTP requests.

## Installing the HTTPClient Component

*But*, we can install *another* package to give us one. At your terminal, type:

```terminal
composer require symfony/http-client
```

*But*, before we run that, I want to show you *where* this command comes from. Search
for "symfony http client". One of the top results will be Symfony.com's documentation
that teaches about Symfony's HTTP Client component. Remember: Symfony is a collection
of *many* different libraries, called components. And *this* one helps you make
HTTP requests!

Near the top, you'll see a section called "Installation", and *there's* the line
from our terminal! If you need a different service to solve a *different* problem,
this is the pattern I follow.

*Anyways*, if we run that... cool! Once it finishes, try that `debug:autowiring`
command again:

```terminal-silent
php bin/console debug:autowiring http
```

And... here it is! Right at the bottom: `HttpClientInterface`, which

> Provides flexible methods for requesting HTTP resources synchronously or
> asynchronously.

## The Super Smart FrameworkBundle

We just got a new service! And *that* means that we must have just installed a
new bundle, right? Because... bundles give us services? Go check out
`config/bundles.php`. Woh! There's *no* new bundle here! Try running

```terminal
git status
```

Interesting: that installed a simple PHP package. Inside `composer.json`, here's
the new PHP library. But it's *just* a library, not a bundle.

So, *normally*, if you install "just" a PHP library, it gives you PHP classes, but
it doesn't hook into Symfony to give you new *services*. What we just saw is a
special trick that many of the Symfony components use. The *main* bundle in our
application is `framework-bundle`. In fact, when we first started our project, this
was the *only* bundle we had. And `framework-bundle` is *smart*. As you install more
Symfony components, like the HTTP Client component, that bundle *notices* the
new library and automatically adds the services for it.

So the new service comes from `framework-bundle`... which adds that service as
soon as it detects that the `http-client` package is installed.

## Using the HttpClientInterface service

Anyways, time to use the new service. The type we need is `HttpClientInterface`.
Head over to `VinylController.php` and, up here in the `browse()` action,
autowire `HttpClientInterface` and let's name it `$httpClient`. Then, instead of
calling `$this->getMixes()`, say `$response = $httpClient->`. This lists all of
its methods... we *probably* want `request()`. Pass this `GET`... and then I'll
paste the URL. You can copy this from the code block on this page: it's a direct
link to the content of that `mixes.json` file.

Cool! So we make the request and it will return a response containing the `mixes.json`
data that we see here. Fortunately, this data has all of the same keys as the
old data we were using down here, so we should be able to swap it in super easily.
To get the mix data from the response, we can say `$mixes = $response->toArray()`:
a handy method that decodes that data for us.

Moment of truth! Move over, refresh and... it works! We now have *six* mixes on
the page. And... super cool! A new icon showed up on the web debug toolbar: "Total
requests: 1". The HTTP Client service hooks *into* the web debug toolbar to add
this, which is pretty awesome. If we click it, we can see info about request
and the response. I *love* this.

To celebrate this working, spin back over and remove the hardcoded `getMixes()`
method.

The only problem I can think of now is that, every single time someone visits our
page, we're making an HTTP request to GitHub's API... and HTTP requests are slow!
To make matters worse, once our site becomes super popular - it shouldn't take
long - GitHub's API will start rate limiting us.

To fix this, let's leverage *another* Symfony service: the cache service.

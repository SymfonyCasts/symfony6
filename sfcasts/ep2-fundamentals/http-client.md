# The HTTP Client Service

We don't have a database yet... and we'll save that for a *future* tutorial. But
to make things a bit more fun, I've created a GitHub repository -
https://github.com/SymfonyCasts/vinyl-mixes - with a `mixes.json` file that holds
a *fake* database of vinyl mixes. Let's make an HTTP request from our Symfony
app *to* this file and use *that* as our temporary database.

So... how *can* we make HTTP requests in Symfony? Well, making an HTTP request is
*work*, and - say it with me now - "Work is done by a service". So the next question
is: Is there already a service in our app that can make HTTP requests?

Let's find out! Spin over to your terminal and run:

```terminal
php bin/console debug:autowiring http
```

to search the services for "http". We *do* get a bunch of results, but... nothing
that looks like an HTTP client. And, that's correct. There is *not* currently any
service in our app that can make HTTP requests.

## Installing the HTTPClient Component

*But*, we can install *another* package to give us one. At your terminal, type:

```terminal
composer require symfony/http-client
```

*But*, before we run that, I want to show you *where* this command comes from. Search
for "symfony http client". One of the top results is Symfony.com's documentation
that teaches about an HTTP Client component. Remember: Symfony is a collection
of *many* different libraries, called components. And *this* one helps us make
HTTP requests!

Near the top, you'll see a section called "Installation", and *there's* the line
from our terminal!

*Anyways*, if we run that... cool! Once it finishes, try that `debug:autowiring`
command again:

```terminal-silent
php bin/console debug:autowiring http
```

And... here it is! Right at the bottom: `HttpClientInterface`, which

> Provides flexible methods for requesting HTTP resources synchronously or
> asynchronously.

## The Super Smart FrameworkBundle

Woo! We just got a new service! *That* means that we must have just installed a
new bundle, right? Because... bundles give us services? Well... go check out
`config/bundles.php`:

[[[ code('31a0dbdbc7') ]]]

Woh! There's *no* new bundle here! Try running

```terminal
git status
```

Yea... that *only* installed a normal PHP package. Inside `composer.json`, here's
the new package... But it's *just* a "library": not a *bundle*.

[[[ code('d946582375') ]]]

So, *normally*, if you install "just" a PHP library, it gives you PHP classes, but
it doesn't hook into Symfony to give you new *services*. What we just saw is a
special trick that many of the Symfony components use. The *main* bundle in our
app is `framework-bundle`. In fact, when we started our project, this was the
*only* bundle we had. `framework-bundle` is *smart*. When you install a new
Symfony component - like the HTTP Client component - that bundle *notices* the
new library and automatically adds the services for it.

So the new service comes from `framework-bundle`... which adds that as soon as it
detects that the `http-client` package is installed.

## Using the HttpClientInterface service

Anyways, time to use the new service. The type we need is `HttpClientInterface`.
Head over to `VinylController.php` and, up here in the `browse()` action,
autowire `HttpClientInterface` and let's name it `$httpClient`:

[[[ code('f67b91f6c3') ]]]

Then, instead of calling `$this->getMixes()`, say `$response = $httpClient->`. 
This lists all of its methods... we *probably* want `request()`. Pass this `GET`... 
and then I'll paste the URL: you can copy this from the code block on this page. 
It's a direct link to the content of the `mixes.json` file:

[[[ code('f33fdc893f') ]]]

Cool! So we make the request and it returns a response containing the `mixes.json`
data that we see here. Fortunately, this data has all of the same keys as the
old data we were using down here... so we should be able to swap it in super easily.
To get the mix data from the response, we can say `$mixes = $response->toArray()`:

[[[ code('b6660098c4') ]]]

a handy method that JSON decodes the data for us!

Moment of truth! Move over, refresh and... it works! We now have *six* mixes on
the page. And... super cool! A new icon showed up on the web debug toolbar: "Total
requests: 1". The HTTP Client service hooks *into* the web debug toolbar to add
this, which is pretty awesome. If we click it, we can see info about the request
and the response. I *love* that.

To celebrate this working, spin back over and remove the hardcoded `getMixes()`
method:

[[[ code('fe38cc18e5') ]]]

The only problem I can think of now is that, every single time someone visits our
page, we're making an HTTP request to GitHub's API... and HTTP requests are slow!
To make matters worse, once our site becomes super popular - which won't take
long - GitHub's API will probably start rate limiting us.

To fix this, let's leverage *another* Symfony service: the cache service.

# Http Client

We don't have a database yet, just some hard-coded data. We'll save that for a *future* tutorial. But to make things a bit more fun, I've created a GitHub repository: `github.com/SymfonyCasts/vinyl-mixes`. This `mixes.json` file  holds a *fake* database of vinyl mixes. I want to make an HTTP request from our Symfony application *to* this file and use *that* as our temporary database. So how can we make HTTP requests in Symfony?

Making an HTTP request is *work*, and - say it with me now - "Work is done by a service". So the next question is: Is there already a service in our application that can make HTTP requests? Let's find out! Spin over to your terminal and run:

```terminal
php bin/console debug autowiring http
```

This will search for "http".

We get a bunch of results here, but... nothing that looks like a HTTP client. And that's actually correct. There's currently *no* service in our application that can help us. So let's install another package that can *give* us that service. To do that, run:

```terminal
composer require symfony/http-client
```

*But*, before I run that, I want to show you where this comes from. If you search for "symfony http client", one of the top results is Symfony.com's documentation that teaches you about Symfony's HTTP Client component. Near the top, you'll see a section called "Installation", and *there's* the line in our terminal! If you need any other services, you can find their installation info the *same* way. A complete Symfony project may have a lot of moving parts, but it starts *small* and we just install things as we need them.

Anyway, if we run that... cool! After it finishes, let's rerun

```terminal
php bin/console debug autowiring http
```
again and... here it is, right at the bottom: `HttpClientInterface` `Provides flexible methods for requesting HTTP resources synchronously or asynchronously`. That's our new service! That means we must have just installed a new bundle, right? Because bundles give us services? Well, in this case, if you check out `config/bundles.php`, there's no new service here at the bottom. If I clear the screen and run

```terminal
git status
```

you can see that what we installed was just a simple PHP library. Inside `composer.json`, here's our new PHP library, but it's *just* a library, not a bundle. Normally, if you just install a PHP library, it gives you PHP classes, but it doesn't hook into Symfony and give you new services. So... how did this PHP library give us new services? Good question! This is a special trick for a lot of Symfony components. The main bundle in our application is `framework-bundle`. When we first started our project, this was the *only* bundle we had. And `framework-bundle` is *smart*. As you install more Symfony components, like the HTTP Client component, if it sees that it has a library, it adds a service for it. In this case, as soon as we installed the HTTP Client library, `framework-bundle` added the new HTTP Client interface service. So the bundle *still* gives us that service... in a roundabout way.

All right, let's use this. The type-hint we need is `HttpClientInterface`. Head over to `VinylController.php` and, up here in the `browse()` action, let's autowire `HttpClientInterface` and call it `$httpClient`. And then, instead of calling `$this->getMixes()`, we're going to replace that with the HTTP Client. We can do this in two steps. First, we can say `$response = $httpClient ->`, and it gives me a list of methods for it. I *probably* want the `request()` method. Then we'll add our `GET` request, and the second argument we need is the URL that we're going to make that request to. I'll paste in that URL, which you can get from the code block on this page. This is like a direct link that will go and get the content of our `mixes.json` file.

Cool! We make the request, which will return this response that will contain the `mixes.json` data that we see here. And fortunately, this data has all of the same keys as the old data we were using down here, so we can swap it without issues. Now, to get the actual mixes, we need to decode the json. We can say `$mixes = $response->toArray()`. That's a handy little function that will automatically decode that data for us.

When we go over and refresh... it works! Now we have *six* mixes that we see on this page, and... super cool! A new icon showed up down on our web debug tool bar: "Total requests: 1". That HTTP Client service hooks into the web debug tool bar to provide us debugging information, which is pretty awesome. If we click this, you can see the exact request as well as some information about that request like the content of the response. This is super helpful for debugging.

To celebrate this working, I'm going to spin back over and remove our hard coded `getMixes()` function. The only problem I can think of with this now is that, on every single request, we're making a HTTP request to GitHub's API. If we deploy it like this, that *could* make our page kind of slow. Not to mention, GitHub's API would eventually rate limit us. So next, let's add some cacheing to this HTTP request.

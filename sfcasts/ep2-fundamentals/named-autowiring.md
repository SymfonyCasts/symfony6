# Named Autowiring

In `MixRepository`, it would be cool if we didn't need to specify the host name down here when we make the HTTP request, and that were, instead, just preconfigured.    Pretty soon, we're also going to configure an access token to be used when we make requests to the GitHub API. How convenient would it be if this HttpClient service came preconfigured to use that access token, instead of us needing to add that header manually? Does symfony have a way for us to preconfigure HttpClient? It *does*. It's called a "scoped client" - a feature of HttpClient where you can create multiple HttpClient services, each preconfigured *differently*.

Here's how it works. Open up `config/packages/framework.yaml`. We *could* put this config in any file like usual, but to create a *scoped* client, you need to put some code under the `framework` key. That's a pretty sensible place to put it. I'll add the config here - `http_client` - followed by `scoped_clients`. Then, give your scoped client a name. I'm going to call this `githubContentClient` because we're using an endpoint to get content for a file. Then we can say `base_uri`. I'll go copy the host name over here... and paste.

Remember, the purpose of these config files is to *change* the services in the container. The end result of this new code is that a second HttpClient service will be added to the container. We'll see that in a minute. And by the way, there's no way that you could just *guess* that you need `http_client` and `scoped_clients` to make this work. This is the kind of thing you'd look for in the documentation, and it would show you how to implement it.

Anyway, now that we've preconfigured this client, we should be able to go into `MixRepository` and make a request directly to the path. But if we head over and refresh... ah...

`Invalid URL: scheme is missing [...]. Did you
forget to add "http(s)://"?`

I didn't *think* we forgot, but apparently we *did*. So this didn't work and you may have already guessed why. Find your terminal and run:

```terminal
php bin/console debug:autowiring client
```

Whoa... There are now *two* HttpClient services in the container: The normal, non-configured one and the one that we *just* configured. And apparently, we're getting the *unconfigured* version. How can I be sure? Well, think back to how autowiring works. Symfony looks at the type-hint of our argument, which is `Symfony\Contracts\HttpClient\HttpClientInterface`, and then looks in the container to find a service whose ID is an exact match. It's *that* simple.

So if there are multiple services with the same type in our container, is only the *main* one autowireable? Surprisingly, no! We can use something called "named autowiring", and we can actually see it right here. If we type-hint an argument with `HttpClientInterface` and call it `$githubContentClient`, we'll get the second one. Check it out! Change the argument from `$httpClient` to `$githubContentClient`, and now... it doesn't work. Whoops...

`Undefined property: App\Service\
MixRepository::$httpClient`

That's just me being careless. When I change the argument, I also need to change the property name. So let's change it down there as well. And now... it works!

Next, let's tackle another tricky problem with autowiring by learning how to fetch one of the *many* services in our container that are *not* available for autowiring.

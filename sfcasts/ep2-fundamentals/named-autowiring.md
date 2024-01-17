# Named Autowiring & Scoped HTTP Clients

In `MixRepository`, it would be cool if we didn't need to specify the host name
when we make the HTTP request. Like, it'd be great if that were preconfigured
and we only needed to include the path. *Also*, pretty soon, we're going to configure
an access token that will be used when we make requests to the GitHub API. We could
pass that access token manually here in our service, but how cool would it be if
the HttpClient service came preconfigured to always include the access token?

So, *does* Symfony have a way for us to, sort of, "preconfigure" the HttpClient
service?  It *does*! It's called "scoped clients": a feature of HttpClient where
you can create multiple HttpClient services, each preconfigured *differently*.

## Creating a Scoped Client

Here's how it works. Open up `config/packages/framework.yaml`. To create a scoped
client, under the `framework` key, add `http_client` followed by `scoped_clients`.
Now, give your scoped client a name, like `githubContentClient`... since we're using
a part of their API that returns the content of files. Also add `base_uri`, go copy
the host name over here... and paste:

[[[ code('ebc7952f44') ]]]

Remember: the purpose of these config files is to *change* the services in the
container. The end result of this new code is that a *second* HttpClient service
will be added to the container. We'll see that in a minute. And, by the way, there's
no way that you could just *guess* that you need `http_client` and `scoped_clients`
keys to make this work. Configuration is the kind of thing where you really need
to rely on the documentation.

Anyways, now that we've preconfigured this client, we should be able to go into
`MixRepository` and make a request directly to the path:

[[[ code('2f3d36b908') ]]]

But if we head over and refresh... ah...

> Invalid URL: scheme is missing [...]. Did you forget to add "http(s)://"?

I didn't *think* we forgot... since we configured it via the `base_uri` option...
but apparently that didn't work. And you may have guessed why. Find your terminal
and run:

```terminal
php bin/console debug:autowiring client
```

There are now *two* HttpClient services in the container: The normal,
non-configured one and the one that *we* just configured. Apparently, in
`MixRepository`, Symfony is still passing us the *unconfigured* HttpClient service.

How can I be sure? Well, think back to how autowiring works. Symfony looks at the
type-hint of our argument, which is
`Symfony\Contracts\HttpClient\HttpClientInterface`, and then looks in the container
to find a service whose ID is an exact match. It's *that* simple

## Fetching the Named Version of a Service

So... if there are multiple services with the same "type" in our container, is only
the *main* one autowireable? Fortunately, no! We can use something called "named
autowiring"... and it's already showing us how. If we type-hint an argument with
`HttpClientInterface` *and* *name* the argument `$githubContentClient`, Symfony
will pass us the second one.

Let's try it: change the argument from `$httpClient` to `$githubContentClient`:

[[[ code('319f5e30f8') ]]]

and now... it doesn't work. Whoops...

> Undefined property: `MixRepository::$httpClient`

That's... just me being careless. When I changed the argument name, it changed
the property name. So... we need to adjust the code below:

[[[ code('2764098093') ]]]

And now... it's alive! We just autowired a *specific* HttpClientInterface service!

Next, let's tackle another tricky problem with autowiring by learning how to fetch
one of the *many* services in our container that is totally *not* available for
autowiring.

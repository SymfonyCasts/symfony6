# Named Autowiring

Coming soon...

In mixed repository, it would be kind of cool. If we didn't need to specify the
host's name down here. When we make the HTTP request, it would be cool. If that were
preconfigured also figured also pretty soon, we're going to configure an access token
to be used. When we make requests to the GitHub API, it would be great. If this HD
client service came preconfigured to use that access token, instead of us needing to
add that header manually, Does Symfony have a way for us to preconfigure HTTP client?
It does. It's called a scoped client, A feature of HTB client, where you can create
multiple HTT client services, each preconfigured differently. Here's how it works.
Open up config packages, framework.YAML. As usual, we could put this config in any
file, but to create a scoped client, you need to put some code under the framework
key. So this is the, This is a pretty sensible place to put it. Here's the config
type HD client then scoped_clients. And then give your scope client a name. I'm going
to call this GitHub Content client because we're using, uh, an endpoint to get
content for a file. Then we can say base_URI = our colon. And then I'm going to go
copy the host name over here

And paste. Now remember the purpose of these config files is to change these services
in the container. The end result of this new code Will be that a second HTTP client
service will be added to the container. We'll see that in a minute. Also, by the way,
there's no way that you could just guess that you need HTB_clients and scoped_clients
to make this work. This is the kind of thing where you'd read the documentation and
it would tell you the configuration that you need Anyways, now that we've
preconfigured this client, we should be able to go into mixed repository and just
make a request directly to the path. But if we head over and refresh, ah, invalid URL
scheme is missing. Did you forget to add HTTV://I didn't. We forgot, but apparently
we did. So this didn't work and you may already be guessing why find your terminal
and run fin console, debug auto wiring Client. And there are now two HTTP client
services in the container, The normal non configured one, and the one that we just
configured. And apparently we are getting the oh, Uh, unconfigured version.

How can I be? Sure. Well, remember how auto wiring works, Symfony looks at the type
of our AR of our argument, which is Symfony contracts, HTB client HTB client
interface, And then looks in the container To find a service whose ID Exactly
matches. That is that It's that simple. Okay. So then if there are multiple services
with the same type In our container is only the main one auto wire belt. Actually,
no, we can use something called named auto wiring, and it's actually showing it to us
right here. If we type in an argument with HD client interface and call it GitHub
content client, we'll get the second one, Check it out, change the argument from ape
client to GitHub content client, And now It doesn't work. Ah, undefined property aids
to be client that's me being careless. When I change the argument, I'm changing the
property name. So let's change it down there as well. And now it works Next. Let's
tackle another tricky problem with auto wiring by learning how to fetch1 of the many
services in our container That are not available for auto wiring.


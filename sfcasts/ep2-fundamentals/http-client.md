# Http Client

Coming soon...

We don't have a database yet. We're just, we just have hard coded data. We'll save
that for a future tutorial, but to make things a bit more fun, I've created a GitHub
repository, Symfony gas /vinyl mixes That holds a sort of fake database of vinyl
mixes in this mixes.JSON file. I want to make an HTTP request from our symp
application to this file and use that as our temporary database. So how can we make
HTTP requests in Symfony? Well, making an HTTP request is work And say it with me now
work is done by a service. So the next question is, is there already a service in our
application that can make HTTP requests? I don't know. Let's find out, spin over and
run bin console, debug auto wiring. And I'm going to search this for, I don't know
how about HTTP? So we get a bunch of results here, but if you look nothing that looks
like an HTTP client. And in fact, that's correct. There's no service in our
application right now that can help us. So let's install a pack, let's install
another package that can give us that service

To get it. We're going to run composer, require Symfony /HTTP client. But before I
run that, I want to show you how I came up with that. If you search for Symfony HTTP
client, You'll come up with a page on Symfony.com's documentation that teaches you
about Symfony's HTTP client component. And the first thing that tells you is how to
install it. Cuz remember Symfony is very big with many parts, but it, our product
starts small and then we install things as we need them.

<laugh>

Anyways, if we run that Cool after it finishes, let's rerun debug auto wiring HTTP
again and oh, here it is right at the bottom HTTP client interface provides flexible
methods for requesting HTTP resources. That is our new service. So that means we must
have just installed a new bundle, right? Because bundles give us services. But the
truth is in this case, if you check out config bundles.PHP, there's no new service
here at the bottom. What we just installed, if I clear the and run get status was
just a simple PHP library Inside composer, JSON, It downloaded this new PHP library,
but it's just a library, not a bundle. So, And normally if you just install a PHP
library that gives you PHP classes, but it doesn't hook into Symfony and give you new
services. So how did this PHP library give us new services? Well, this is a special
trick for a lot of these Symfony components. The main bundle in our application is
framework bundle. When we actually, when we first started our project, this was the
only bundle we had. And that framework bundle is smart. It actually detects as you
install more Symfony components like the HTTP client component. And if it sees that
that library, it adds a service for it.

So it's still true that services come from bundles in this case. As soon as we
installed HTTP, the HTTP client library framework bundle added the new HTB client
interface service. So there's still a bundle that gives us that service. Anyways,
let's use this. So the Type pent weed is HTB client interface. So let's go over to
vinyl controller And up here in the browse action, let's Otta wire H HTTP client
interface. We'll call it HTP client. And then instead of calling this-> get mixes,
We're going to replace that with the HTP call. So we're going to do this in two
steps. First response = HDB client and then like normal. I can just hit->and it tells
me what methods are on it. I probably want the method request. So we are going to
make a get request. First argument is the method. So a get request. Second argument
is the URL that we're going to make that request too. I'm going to paste in a URL.
You can get this from the code block on this page. This is like a direct link that
will go and get the content of that mixes.JSON file.

Cool. So we make the request. That's going to give us back this response. That's
going to contain the JS O data that we see here. And fortunately, this JS O data has
all of the same keys as the old data we are using down here. So we can swap it in
without any problems. Now to get the actual mixes we need to decode the JS O we can
say mixes = response,->to array. That's a handy little function that will
automatically J on decode that data for us.

Mm-hmm <affirmative>.

So now when we go over and refresh, It works automatically. Now we have six mixes
that we see on this page And super cool. A new icon showed up down on our web debug
tool bar. Ooh, total requests, one that HTTP client service hooks into the web debug
tool bar to provide us debugging information, which is pretty cool. If we click this,
you can see the exact request and even information about that request and like what
the response was for that request. Super good for debugging To celebrate this
working, I'm going to spin back over and remove our hard coded, get mixes function.
Bye bye. Now, healing problem. I can think of with this is that on every single
request we're making an, a, an HTTP request over to GitHub's API. If we deploy this
that's could get kind of making an HD request is kind of heavy that can make our page
kind of slow not to mention we would eventually hit, uh, GitHub's API would
eventually rate limit us. So next Let's add some Cing to this HT, to this HDB
request.

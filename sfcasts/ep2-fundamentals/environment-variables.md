# Environment Variables

Coming soon...

Open config packages, `framework.yaml`. We don't need to be authenticated to use this
raw GitHub user content part of GitHub's API. But if we hit this end point a lot, we
might hit their rate-limiting, Which is pretty low for anonymous users. So let's
authenticate our request. First, if you're coding along with me, head to github.com
and create your own personal access token, Then head into mixed repository and go
down to our API call. The way that you Attach the access token to your request is you
pass a third argument to request, which is an, uh, an array pass the, say `header`
option, And then pass an `Authorization` header, Set the word 'token', and then your
personal access token. Start by just putting something fake. Now you can tell that
this is working because when you go back over to your page and refresh it explodes,
our API call now fails with a four oh four because it recognizes we're trying to pass
a token, but we pass an invalid token. So now put in your real token, Try it again.
And it's working and behind the scenes, we're now going to have a much higher, uh,
rate limit.

So this is cool, but it would be nicer, especially if we want to use this service,
this Php client service in multiple places. If the service came preconfigured to
automatically set this authorization `header`, and we can totally do that. Copy this
token line header the `framework.yaml` And after base, right? We can pass a headers
key To `authorization` set to our long stream. Actually let me put a fake token in
first, temporarily, And then back in our service, let's remove that third argument.
Awesome. And then go over and verify that things are broken. Perfect. That proves
that we are sending that header just as the wrong value. So if we change it to our
real token, Once again, this works so, so far, this is just a nice feature of the
HTTP client. Yay, but it exposes a problem. It's not super great to have my sensitive
GitHub API token hard coded in this file. I mean, this is going to be a file. That's
committed to our repository. I love my teammates, but I don't want them to have an
access token that gives 'em access to my account. This is where environment variables
come in handy. So if you're not familiar with environment variables, there are
variables that you can set on any system, windows, Linux, whatever,

And then your web server. And so your PHP code can read those in And many hosting
platforms Give you a super easy way to set them. So before we talk about setting
environment variables first, how do we read environment variables in Symfony? Copy
your access token. So you don't lose it, Put quotes around token, and then where we
wannna user our token. We're going to use a very special syntax here. It's actually
going to look like a parameter. So it's going to start and `%env` but it's going to
be `env( )`. And then the name of the environment variable is street. How about
`(GitHub_token)%`? I just made that up. It can be anything you want. So that's great.
We're now reading that in GitHub token environment variable, But of course we haven't
set that environment variable yet. So if we refresh, it says environment variable not
found `GitHub_token`. So in the real world setting environment, variables is actually
kind of tricky. It's different on windows versus on Linux. Many hosting platforms
make it super easy to set environment variables, but it's not very easy to do locally
on your computer.

That is why this `.env file` exists. Very simply when Symfony boots up, it reads the
.env file. And these all turn into environment variables. In other words, we can set
`GitHub_token=` and paste, And now it works By the way, if we D if there were a real
environment variable set that was `=GitHub_token` that real environment variable
would win over what we have in this file. Okay, cool. But we still ha, but we still
have the same problem. We have this sensitive value that's inside of a file. That's
committed to the repository. We do commit the `.env file` to the repository To fix
that. Copy the GitHub token, delete its value from this file. And now create a new
file Called `.env.Local` and then paste. And now things still work. So when Symfony
boots out, it first reads the `.env file` and makes these environment variables. And
then it reads `.env.local`

Where our github token in here overrides the sort of fake environment variables set
in .env This is a pattern you'll see a lot. Your .env file is really meant to hold,
uh, safe values that can be committed to the repository then locally, and maybe also
on production, depending on how you deploy. You create `.env.local` and paste the
file there. The key thing is that `.env.Local` is ignored by Git. You can see it's
already in our git ignore files. This is not something that's going to be committed
to the repository. There are a few other .env.files you can create, and you can kind
of see them mentioned here. They're not as important. If you want to read about them,
you can open the documentation. Oh, and one other cool thing about environment
variables is you can visualize them with the `debug:dotenv` command. So this is cool.
You can see that GitHub token's current value is set to this and in `.env.local`
`.env.local`, it's also set to this. So you can see that it's being overridden.
Whereas the `App_Env` `App_secret`, these are their values and they are not being
overridden in `.env.dev.local`. And I can also tell you like which of the, .env files
it detected.

There are a few other tricks you can use with environment variables. Uh, for example,
there's a system called a processing processor system Where you could like use `trim`
to trim the white space off of Github_token", or even use "file:and maybe
github_Token" is actually pointing at a, the github token environment. Variable is
actually a path to a file and then file this file thing. Loads it anyways, there's
some tricks in processing you can do with these they're called in processors. If you
need to use them. All right, next, let's talk quickly about deployment, but even more
about how we can safely store These production secret values inside of Symfony's
secrets vault.

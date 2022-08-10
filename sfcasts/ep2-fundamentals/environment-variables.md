# Environment Variables

Open `config/packages/framework.yaml`. We don't need to be authenticated to use this
raw user content part of GitHub's API:

[[[ code('d0e2649747') ]]]

But if we hit this endpoint a lot, we *might* hit their rate-limiting, which is pretty low 
for anonymous users. So let's *authenticate* our request.

## Adding an Authorization Header to the HTTP Request

First, if you're coding along with me, head to "github.com" and create your own
personal access token. Once you've done that, open up `MixRepository` and go down
to where we make the HTTP request. To attach the access token to the request pass
a *third* argument, which is an array. Inside, add a `headers` key set to another
array, with an `Authorization` header assigned to the word `Token` and then the
access token. Start by using a fake token:

[[[ code('9cb7084ddc') ]]]

You can tell this is working because, when we go back over to the page and
refresh... it explodes! Our API call now *fails* with a 404 because it recognizes
that we're *trying* to authenticate with a token... but the one we passed is
*bogus*.

Now add your *real* token. Try it again and... it works!

## Moving Authorization Header to framework.yaml

So this is cool! *But* it would be nicer if the service came preconfigured to
*automatically* set this authorization header... especially if we want to use this
HTTP Client service in multiple places. Can we do that? You bet!

Copy the `Token` line, head into `framework.yaml`, and after `base_uri`, pass
a `headers` key with `Authorization` set to our long string. Actually, let me put a
*fake* token in there temporarily:

[[[ code('63d8ced9e1') ]]]

Back in `MixRepository`, remove that third argument:

[[[ code('614d5f78d4') ]]]

And now, when we try this... great! Things are broken, which proves we're sending
that header... just with the wrong value. If we change to our *real* token... once
again... it works! Awesome!

## Hello Environment Variables

So far, this is just a nice feature of the HttpClient. But this also helps highlight
a common problem. It's... not super great to have our sensitive GitHub API token
hardcoded in this file. I mean, this file is going to be committed to our
repository. I love my teammates... but I don't love them *so* much that I want to
share my access token to with them... or the access token for our company.

This is where *environment variables* come in handy. If you're not familiar with
environment variables, they're variables that you can set on any system (Windows,
Linux, whatever.)... and then you can read them from inside of PHP. Many hosting
platforms make it super easy to set these. How does that help us? Because, in theory,
we could set our access token as an *environment* variable then simply *read* it
in PHP. That would let us *avoid* putting that sensitive value *inside* our code.

## Reading Environment Variables

But, before we talk about *setting* environment variables, how do we
*read* environment variables in Symfony? Copy your access token so you don't lose
it, put single quotes around `Token`, and then we're going to use a very special
syntax to *read* an environment variable. It's actually going to look like a
parameter. Start and end with `%`, and inside, say `env()` with the name of the
environment variable. How about `GITHUB_TOKEN`. I just made that name up:

[[[ code('812eee7285') ]]]

If we head back and refresh... we are now *reading* that `GITHUB_TOKEN` environment
variable... but we haven't *set* it yet, so we get this "Environment variable not
found" error.

## Setting Environment Variables & .env

In the real world, setting environment variables is... actually kind of tricky. It's
different on Windows versus Linux. And while many *hosting* platforms *do* make it
super easy to set environment variables, it's not very simple to do *locally* on
your computer.

*That* is why this `.env` file exists. Very simply, when Symfony boots up, it reads
the `.env` file and turns all of these into environment variables. This means we
can say `GITHUB_TOKEN=` and paste our token... and now... it works!

[[[ code('6de74e6cd7') ]]]

By the way, if there were a *real* `GITHUB_TOKEN` environment variable set on my
system that real environment variable would win over what we have in this file.

## The .env.local File

Okay... this is cool... but we *still* have the same problem! We have a sensitive
value that's inside of a file... which *is* committed to our repository.

Ok, then, let's try something else. Copy the GitHub token, delete the value from
this file, and then create a new file called `.env.local`. Set the environment
variable *here*.

And now... things *still* work!

Here's the deal. When Symfony boots up, it first reads the `.env` file and turns
all of these into environment variables. *Then* it reads `.env.local` and turns
anything in *here* into environment variables... which *override* any values set
in `.env`.

The result is that your `.env` file is meant to hold safe, default values that are
ok to be committed to your repository. Then locally, (and maybe also on production,
depending on how you deploy), you create a `.env.local` file and put the sensitive
values  there. The *key* thing is that `.env.local` is *ignored* by Git. You can
see it's already in our `.gitignore` file. So while this file *will* contain
sensitive values, it will *not* be committed to the repository.

There *are* a few other `.env` files that you can create... and you can see them
mentioned here. They're not as important, but if you want to read about them, you
can check out the documentation.

## Visualizing Env Vars with debug:dotenv

Another cool thing about environment variables is that you can visualize them by
running:

```terminal
php bin/console debug:dotenv
```

Sweet! You can see the current value of `GITHUB_TOKEN`... and that this value
is also set in `.env.local`. In contrast, `APP_ENV` and `APP_SECRET` have `n/a`
here, meaning their values are *not* being overridden in `.env.local`. It also
tells us which `.env` files it detected.

## Env Var Processors

There are a few tricks you can use with environment variables. For example,
there's something called a "processor system" where you could use `trim` to "trim"
the white space on `GITHUB_TOKEN`. *Or* you could use `file` where the `GITHUB_TOKEN`
variable is actually a path to a file that contains the true value. Anyways, these
are called "env var processors" if you want to read more about them.

Next, let's talk quickly about deployment... but even more about how we can safely
store these sensitive values when you deploy to production. One option is Symfony's
secrets vault.

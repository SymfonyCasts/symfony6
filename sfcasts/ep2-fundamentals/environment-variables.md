# Environment Variables

Open `config/packages/framework.yaml`. We don't need to be authenticated to use this raw GitHub user content part of GitHub's API. But if we hit this endpoint a lot, we *might* hit their rate-limiting, which is pretty low for anonymous users. So let's *authenticate* our request.

First, if you're coding along with me, head to "github.com" and create your own personal access token. Then head into `MixRepository` and go down to our API call. The way you attach the access token to your request is by passing a *third* argument to your request, which is an array. Inside our array, say `'headers' =>`, and below that, pass an `Authorization` header set to `Token` along with your personal access token. Start by just adding something fake.

You can tell this is working because when you go back over to your page and refresh... it explodes! Our API call now *fails* with a 404 because it recognizes that we're trying to pass a token, but the one we passed is an *invalid* token we made up. Now add your *real* token. Try it again and... it's working! This will help us avoid going over Github's rate-limit as well.

Super cool! *But* it would be nicer if the service came preconfigured to *automatically* set this authorization header, especially if we want to use this PHP Client service in multiple places. Can we do that? You bet!

Copy this `Token` line, head into `framework.yaml`, and after `base_uri`, we can pass a `headers` key with `Authorization` set to our long string. Actually, let me put a *fake* token in there temporarily. And back in our service, let's remove that third argument. And now, go over and verify that things are broken. Perfect! That proves we're sending that header, just with the wrong value. If we change it to our *real* token... once again... this works! Awesome!

So far, this is just a nice feature of the HttpClient. But this also exposes a problem. It's not super great to have my sensitive GitHub API token hard coded in this file. I mean, this is going to be a file that's committed to our repository. I love my teammates, but I don't want them to have an access token that gives them access to my account. This is where *environment variables* come in handy. If you're not familiar with environment variables, they're variables that you can set on any system (Windows, Linux, etc.), and then your web server (via your PHP code) can read those in. And many hosting platforms give you a super easy way to set them.

Before we talk about *setting* environment variables, how do we *read* environment variables in Symfony? Copy your access token so you don't lose it, put single quotes around `Token`, and then we're going to use a very special syntax where we want to use our token. It's actually going to look like a parameter. So it's going to start and end with `%`, and inside I'll say `env()` with the name of the environment variable it should read. How about `GITHUB_TOKEN`? I just made this up, and you can call it whatever you want. If we head back and refresh... we are now *reading* that `GITHUB_TOKEN` environment variable, but we haven't *set* it to anything yet, so we get this `Environment variable not found ` error.

In the real world, setting environment variables is actually kind of tricky. It's different on Windows versus on Linux, and while many hosting platforms make it super easy to set environment variables, it's not very easy to do locally on your computer. That is why this `.env` file exists. Very simply, when Symfony boots up, it reads the `.env` file and these all turn into environment variables. In other words, we can set `GITHUB_TOKEN=` and paste our token here. And now... it works! By the way, if there were a *real* environment variable set that was equal to `GITHUB_TOKEN`, that real environment variable would win over what we have in this file.

Okay... that's cool, but we still have the same problem. We have this sensitive value that's inside of our `.env` file, which *is* committed to the repository. To fix that, copy the GitHub token, delete the value from this file, and then create a new file called `.env.Local`. Paste that value in the new file, and now... things *still* work!

When Symfony boots up, it first reads the `.env` file and makes these environment variables. *Then* it reads `.env.local`, where the `GITHUB_TOKEN` in *here* overrides the environment variable set in `.env`. This is a pattern you'll see a lot. Your `.env` file is really meant to hold safe values that can be committed to the repository. Then locally (and maybe also on production, depending on how you deploy), you create `.env.local` and paste the values there. The *key* thing is that `.env.local` is *ignored* by Git. You can see it's already in our `.gitignore` file, which means this is *not* something that's going to be committed to the repository. There are a few other `.env` files you can create, and you can see them mentioned here. They're not as important, so if you want to read about them, you can check out the documentation.

Another cool thing about environment variables is that you can visualize them with the

```terminal
php bin/console debug:dotenv
```

command. Here you can see that `GITHUB_TOKEN`'s current value is set to this, and in `.env.local`, it's *also* set to this. So you can see that it's being overridden. In contrast, `APP_ENV` and `APP_SECRET` have `n/a` here, meaning their values are not being overridden in `.env.local`. And it can also tell you which `.env` files it detected.

There are a few other tricks you can use with environment variables. For example, there's something called a "processor system" where you could use `trim` to "trim" the white space on `GITHUB_TOKEN`. *Or* you could use `file` where the `GITHUB_TOKEN` variable is actually a path to a file and then `file` loads it. Anyways, there are some tricks you can do with these, and they're called "env var processors" if you want to read more about them.

Next, let's talk quickly about deployment, but even more about how we can safely store these production secret values inside of Symfony's secrets vault.

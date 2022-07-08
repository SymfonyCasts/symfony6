# Secrets Vault

I don't want to get *too* far into deployment, but let's do a quick "How To Deploy Your Symfony App 101" course. Here's the idea.

Step 1 : You need to *somehow* get all of your committed code onto your production machine and then run

```terminal
composer install
```

to populate the `/vendor` directory.

Step 2: Somehow create a `.env.local` file with all of your production values,which will include `APP_ENV=prod`, so that you're in the prod environment.

And Step 3: Run

```terminal
php bin/console cache:clear
```

which will clear the cache in the production environment, and

```terminal
php bin/console cache:warmup
```

which will warm up your cache. There may be a few other commands like running your database migrations, but this is the general idea, and the Symfony docs will always have more details. By the way, if you're wondering, we use Symfony's official `platform.sh` solution for deployment, which handles *a lot* of stuff for us. You can check it out by going to `symfony.com/cloud`. It also helps support the Symfony project so it's a win-win.

Anyway, the trickiest part of the process is Step 2 - creating the `.env.local` file with all of your production values. If your hosting platform allows you to store *real* environment variables directly inside of it, problem solved! Do that and you don't need a `.env.local` file at all. But if that's not an option for you, you'll have to figure out how to give your deployment system access to all of your sensitive environment values, like your GitHub access token and database credentials, so that it can create the `.env.local` file. But since we're not committing any of these values to our repository, where should we store them? One option for handling sensitive values is Symfony's *secrets vault*. It's an encrypted set of `.env` files that contain all of your secrets but is *safe* to commit to your repository. Hehe... "safe"...

If you want to create a secrets vault, you'll need two of them - one for the dev environment and one for the production environment. So let's start by creating one for the dev environment. Run

```terminal
php bin/console secrets:set
```

and then pass this `GITHUB_TOKEN`, which is the value we want to set. It's going to ask for our "secret value" and since this is the secret for the dev environment, we'll want to put something that's safe for everyone to see. I'll explain why in a moment. I'll say `CHANGEME`. You can't see me type that, but that's what I just typed there. And since this is the first secret we've created, it automatically created the secrets vault behind the scenes, which is quite literally a set of files that live in `/config/secrets/dev`. For the dev vault, we're going to commit all of these files to the repository. So I'm going to add that entire secrets directory and say:

```terminal
git commit -m "adding dev secrets vault"
```

Here's a quick explanation of the files we're looking at. This `dev.list.php` just lists what's inside of it, `dev.GITHUB_TOKEN.28bd2f.php` stores the encrypted secret value, and `dev.encrypt.public.php` is actually the key that allows developers and your team to add more secrets to the system. So if another developer pulled down the project, they'll have this file, so they can add more secrets. And finally, `dev.decrypt.private.php` is the secret key that allows you to decrypt the tokens.

We *did* just commit the `decrypt` key to the repository. That would *normally* be a no-no, but this is our *dev* vault, which means we're only going to store values that are safe for *all* developers to look at. The dev vault will only be used locally for development on production. When we're in the *prod* environment, our app is going to need a *prod* vault. To create this, run:

```terminal
./bin/console secrets:set GITHUB_TOKEN --env=prod
```

This time, I'm going to go grab my real value from `.env.local` and paste that here. And just like before, since there wasn't a prod vault yet, it *created* it and it's got the *same* four files as before. But there *is* one subtle difference.

Let's add that new directory to Git. When I'm writing

```terminal
git status
```

only *three* of the files are added. The *fourth* file - the `decrypt` key - is actually *ignored* from Git. You already have this inside of `.gitignore`. We do *not* want to commit the `prod` key to the repository. Anyone that has the `prod` key will be able to read all of our secrets. So on production, the only thing you need to worry about is getting this `prod.decrypt.private.php` file up on your server. Alternatively, you can read this `private` key and set it as a special environment variable. So instead of having to worry about *lots* of different environment variables, you only need to worry about *one*.

All right, in the dev environment, Symfony will automatically use the dev vault. In the *prod* environment, it will use the *prod* vault. Both of these now have a `GITHUB_TOKEN`. The question is: How do we read secrets out of the vault? The answer is... we already *are*. Secrets *become* environment variables. It's as simple as that! So in `config/packages/framework.yaml`, by using this `env` syntax here, this `GITHUB_TOKEN` could be a real environment variable, or it could be a *secret* in our vault.

To see if this is working, head to your `MixRepository` service, and let's `dd($this->githubContentClient)`. Head over here, refresh, and... I want to see if we can find the header in there. There's actually a really cool trick with this component. If you click on this area and hold "command" or "control" + "F", you can search inside of it. I'm going to search for the word "token" and... oh, that's not right. This is our *real* token, but since we're in the *dev* environment, it should be reading our *dev* vault where we have our fake "CHANGEME" value. So... what's going on?

As I mentioned, secrets become environment variables. *But* environment variables take precedence - even environment variables that are defined in `.env` files. Momentarily, there *is* a `GITHUB_TOKEN`, so instead of using the one from our vault, it's using the one from our `.env` system.

Here's the point. As soon as you want to convert a value from an environment variable into a secret in your vault, you need to refrain from using it as an environment variable at *all* anymore. In other words, delete `GITHUB_TOKEN` in `.env` and *also* in `.env.local`. Go refresh, click on this again, use "command" + "F", search for "token", and... got it! We see "CHANGEME". If we were in the prod environment, it would read the value from the prod vault.

Okay, cool. Let's remove that `dd` and refresh to discover that... locally, everything is broken. Dang! Of course! It's using that *fake* token from the *fake* vault. So how can we fix our app locally? Could we just override the local `GITHUB_TOKEN` value temporarily, but be *extra careful* and not commit that value? No, that's lame. Before we fix this, I'm going to show you a really handy command when it comes to the vault: `php bin/console secrets:list`. This shows you your secret values. Pretty cool! And you can even pass `--reveal` to *reveal* the value, as long as you have the `decrypt` key locally.

And you may have noticed that it *gives* us the value right here, but here, it just says "Local Value" with a blank space. Hmm... Let's re-run the same command, but this time add `--env=prod`. And... same thing! Now we can see our *real* `prod` key and there's still this "Local Value" with nothing in this space. The secret system has a way to only override things locally. And as a shortcut, you can actually use `secrets:set --local` to do that. I'll copy the real key up here, and then run:

```terminal
php bin/console secrets:set GITHUB_TOKEN --local
```

Then, you won't see it, but I'm going to paste the value here and hit "enter". I'll copy that value, and to set this locally, head over to `.env.local`. Right here, let's say `GITHUB_TOKEN=` and paste the value we just copied. We're going to locally take advantage of the fact that environment variables override secrets. If we go to our terminal and run

```terminal
php bin/console secrets:list --reveal
```

again, you can see that the *official* value in the vault is "CHANGEME", but the *local* value is our real token which, as we know, will override the secret and be used locally.

Okay, team! We're... well... *basically* done! So as a reward for your hard work on these *super* important but sometimes tough topics, let's celebrate by using Symfony's code generator library: MakerBundle.

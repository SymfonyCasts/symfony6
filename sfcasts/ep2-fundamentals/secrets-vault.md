# The Secrets Vault

I don't want to get *too* far into deployment, but let's do a quick "How To Deploy
Your Symfony App 101" course. Here's the idea.

## Deployment 101

Step 1: You need to *somehow* get all of your committed code onto your production
machine and then run

```terminal
composer install
```

to populate the `vendor/` directory.

Step 2: Somehow create a `.env.local` file with all of your production environment
variables, which will include `APP_ENV=prod`, so that you're in the prod
environment.

And Step 3: run

```terminal
php bin/console cache:clear
```

which will clear the cache in the production environment, and then

```terminal
php bin/console cache:warmup
```

to "warm up" the cache. There may be a few other commands, like running your
database migrations... but this is the general idea. And the Symfony docs have
more details.

By the way, in case you're wondering, we deploy via https://platform.sh, using
Symfony's Cloud integration... which handles *a lot* of stuff for us. You can
check it out by going to https://symfony.com/cloud. It also helps support the
Symfony project, so it's a win-win.

## Use Real Environment Variables When Possible

Anyway, the trickiest part of the process is Step 2 - creating the `.env.local`
file with all of your production values, which will include things like API keys,
your database connection details and more.

Now, *if* your hosting platform allows you to store *real* environment variables
directly inside of it, problem solved! If you set *real* env vars, then there is
no need to manage a `.env.local` file at all. As soon as you deploy, Symfony will
instantly see and use the real env vars. That's what we do for Symfonycasts.

## Creating .env.local During Deploy?

*But* if that's *not* an option for you, you'll need to somehow give your
deployment system access to your sensitive values so that it can create the
`.env.local` file. But... since we're not committing any of these values to our
repository, where *should* we store them?

One option for handling sensitive values is Symfony's *secrets vault*. It's a
set of files that contain environment variables in an *encrypted form*. These
files are *safe* to commit to your repository... because they're encrypted!

## Creating the dev Vault

If you want to store secrets in a vault, you'll need two of them: one for the
`dev` environment and one for the `prod` environment. We're going to *create*
these two vaults first... *then* I'll explain how to read values out of them.

Start by creating one for the `dev` environment. Run:

```terminal
php bin/console secrets:set
```

Pass this `GITHUB_TOKEN`, which is the secret we want to set. It then asks for our
"secret value". Since this is the vault for the `dev` environment, we want to
put something that's safe for everyone to see. I'll explain why in a moment. I'll
say `CHANGEME`. You can't see me type that... only because Symfony hides it for
security reasons.

Since this is the *first* secret we've created, Symfony automatically created the
secrets vault behind the scenes... which is literally a set of files that live
in `config/secrets/dev/`. For the *dev* vault, we're going to commit *all* of
these files to the repository. Let's do that. Add the entire secrets directory:

```terminal-silent
git add config/secrets/dev
```

Then commit with:


```terminal
git commit -m "adding dev secrets vault"
```

## The Secrets Vault Files

Here's a quick explanation of the files. `dev.list.php` stores a list of *which*
values live inside the vault, `dev.GITHUB_TOKEN.28bd2f.php` stores the actual
encrypted value, and `dev.encrypt.public.php` is the cryptographic key that allows
developers on your team to add *more* secrets. So if another developer
pulled down the project, they'll have this file... so they can add more secrets.
Finally, `dev.decrypt.private.php` is the secret key that allows us to *decrypt*
and *read* the values in the vault.

As *soon* as the vault files are present, Symfony will automatically open them,
decrypt the secrets, and expose them as environment variables! But, more on that
in a few minutes.

## Storing the dev Decrypt Key?

But wait: did we really just *commit* the `decrypt` key to the repository? Yes!
That would *normally* be a no-no! Why would you go to the trouble of encrypting
values... just to store the decryption key right next to them?

The reason we're doing *exactly* that is that this is our *dev* vault, which means
we're only going to store values that are safe for *all* developers to look at.
The `dev` vault will only be used local development... and we want our teammates
to be able to pull down the code and read those without any trouble.

Ok, at this point we have a `dev` vault that Symfony will automatically use in
the `dev` environment. Next: let's create the *prod* vault, which will hold the
*truly* secret values. We'll then learn relationship between vault secrets and
environment variables... as well as an easy way to visualize all of this.

# Reading Secrets vs Env Vars

We *just* created a secrets vault for our `dev` environment... which will contain
a default "safe" version of any sensitive environment variables. For example,
we set the `GITHUB_TOKEN` value to `CHANGEME`.

*Now* let's create the `prod` environment vault. Do that by saying:

```terminal
./bin/console secrets:set GITHUB_TOKEN --env=prod
```

This time, grab the *real* secret value from `.env.local` and paste it here.
Just like before, since there wasn't a `prod` vault already, Symfony *created*
it. And it's got the *same* four files as before. Though, there *is* one subtle,
but important difference.

Add that new directory to git:

```terminal-silent
git add config/secrets/prod
```

Then run:

```terminal
git status
```

Woh! Only *three* of the four files were added. The *fourth* file - the `decrypt`
key - is *ignored* by Git. We already have a line inside in `.gitignore` for that.
We do *not* want to commit the `prod` decrypt key to the repository... because
anyone that has this key will be able to read *all* of our secrets.

So, if another developer pulls down the project now, they *will* have the `dev`
decrypt key, so they'll have no problems reading values from the `dev` vault.
They won't have the `prod` decrypt key... but no big deal! The only place where
you need the `prod` decrypt key is on production!

So with this setup, when you deploy, instead of needing to create an entire
`.env.local` file containing *all* of your secrets, you just need to worry about
getting this *one* `prod.decrypt.private.php` file up into your code. Or,
alternatively, you can read this key and set it on an environment variable:
you can check the docs for details on how.

## Using The Secrets Vault

But... wait a second. I haven't really explained *how* the vault is used! We know
that the `dev` environment will use the `dev` vault... and `prod` will use
`prod`... but how do we *read* secrets out of the vault?

The answer is... we already *are*! Secrets *become* environment variables. It's
as simple as that! So in `config/packages/framework.yaml`, by using this `env`
syntax, this `GITHUB_TOKEN` could be a *real* environment variable, or it could
be a *secret* in our vault.

To see if this is working, head to `MixRepository` and
`dd($this->githubContentClient)`:

[[[ code('53828c9ece') ]]]

Move over, refresh, and... let's see if we can find the Authorization header in
this. Actually, there's a really cool trick with dump. Click on this area and
hold "command" or "control" + "F" to search inside of it. Search for the word
"token" and... oh, that's not right! That's our *real* token. But... since
we're in the *dev* environment, shouldn't it be reading our *dev* vault where we
set the fake `CHANGEME` value? What's going on?

## Secrets Must Fully Be Converted Away from Env Vars

As I mentioned, secrets become environment variables. *But* environment variables
take *precedence* over secrets: even environment variables defined in the `.env`
files. Yup, because we have a `GITHUB_TOKEN` env var set in `.env` and
`.env.local`, *that* is taking precedence over the value in the vault!

Here's the point. As soon as you choose to convert a value from an environment
variable into a secret, you need to *stop* setting it as an environment
variable *completely*. In other words, delete `GITHUB_TOKEN` in `.env` and *also*
in `.env.local`.

Go refresh, click on this again, use "command" + "F", search for "token", and...
got it! We see "CHANGEME"! If we were in the `prod` environment, it would read the
value from the prod vault... assuming the prod decrypt key was available.

## The secrets:list Command

Ok, remove that `dd()` and refresh to discover that... locally, everything is
broken! Dang! But... of course! It's now using that *fake* token from the *dev*
vault. It *would* work ok on production... but how can I fix my local setup so
I can keep working?

We *could* temporarily override the `GITHUB_TOKEN` secret value in the `dev` vault
by running the `secrets:set` command. But... that's lame! We would need to be
extra careful to not commit the modified, encrypted file.

Before we fix this, I want to show you a really handy command for the vault:

```terminal
php bin/console secrets:list
```

Yup, this shows you all of the secrets in our vault. Pretty cool! And you can even
pass `--reveal` to *reveal* the value... as long as you have the `decrypt` key.

You may have noticed that it *gives* us the value right here... but then says
"Local Value" with a blank space. Hmm...

Re-run the command, but this time add `--env=prod`.

```terminal-silent
php bin/console secrets:list --reveal --env=prod
```

And... same thing! This shows us the *real* `prod` value... but there's still
this "Local Value" spot with nothing.

This "Local Value" is the key to fixing our broken dev setup: it's a way to
override a secret, but only *locally* on our one machine.

How do you *set* this local override value? Copy the real `GITHUB_TOKEN` value,
then move over, find `.env.local` - the same file we've been working in - and say
`GITHUB_TOKEN=` and paste the value we just copied.

Yup! Locally, we're going to take advantage of the fact that environment variables
"win" over secrets! Back at your terminal, run

```terminal
php bin/console secrets:list --reveal
```

again. Yes! The *official* value in the vault is "CHANGEME"... but the *local*
value is our *real* token which, as we know, will *override* the secret and be
used. If we try the page again... it works!

Okay, team! We're... well... *basically* done! So as a reward for your hard work
on these *super* important topics, let's celebrate by using Symfony's code
generator library: MakerBundle.

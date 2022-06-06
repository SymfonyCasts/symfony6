# Secrets Vault

Coming soon...

I don't want to get too far into deployment, but let's do a quick, how to deploy your
Symfony app one and one course. Here's the idea. Step one. You need to somehow get
all of your code Committed code Onto your production machine and then run composer
install to populate the vendor directory. Step two, somehow create a '.env.local'
file with all of your production values in step three, Which will include
`APP_ENV=prod` that you're in the prod environment. And step three, Run `Php
bin/console cache:clear`, Which will clear cache in the production environment and
`cache:warmup`, which will help warm up your cache. There may be a few other commands
like running your database migrations but this is the general idea and the symfony
docs have more details, man, by the way, if you're wondering, we use Symfony's
official `platform.sh` solution For deployment, Which handles a lot of things for us.
You can check it out by going to `symfony.com/cloud`. It also helps support the
symfony project. Anyways, the trickiest part of the process is step two, creating the
`.env.local file` with all of your production values. If your deployment, if your
hosting platform allows you to store real environment variables directly inside of
it, problem solved do that, and you don't need a `.env.local` file at all. But
otherwise, somehow our deployment system needs to have access to all of our Sen
sensitive environment, Val, uh,

Values like our GitHub access token and our database credentials so that it can
create the `.env.local` file. But since we're not committing any of these values to
our repository, where should we store them? One option for handling sensitive values
is symfony's secrets vault. It's an encrypted set of env files that contain our
secrets but are safe to commit to your repository. All right, here's how it works. If
you want to create a Secrets vault, you'll actually have one for the dev environment
and one for the production environment. So let's start by creating one in the dev
environment, Run `./bin/console secrets:set`, And then pass this `GitHub_token`. The
value that we want to set, It's going to ask for our secret value And what you're
going to want to do here since this is the dev in, uh, this is the secret for the dev
environment. It's put something that it's safe for everyone to see. I'll tell you why
in a second. So I'm going to put change me. You can't see me type that, but that's
what I just typed there. And since this is the first secret that we've created, it
automatically created the secrets vaults behind the scenes, which is quite literally
a set of files live in config, secrets, dev,

And then a number of files In the, for the dev vault. We are, are going to commit all
these files to the repository. So I'm going to add that entire secrets directory And
say, adding dev secrets vault. Now a quick explanation of these files `Dev.list` just
lists what's inside of it. `Dev.github_token` actually stores the encrypted secret
value. Then this `dev.encrypt.public` Key Is actually the key that allows developers
and your team to add more secrets to the system. So if another developer pulled down
the project because they'll have this file, they can add more secrets. And finally,
`dev.decrypt` is actually the secret key that allows you to decrypt github_tokens. So
notice that we did just commit the decrypt key to the repository. That would normally
be a no-no, but this is our dev vault, which where we're only going to store values
that are safe for all the developers to look at, But the dev Vault's just going to be
used locally for development on production. When we're in the prod environment, our
app is going to need a prod vault. So run secret set again, but past `--env=prod`.

And this time I'm going to go grab my real value, from, local, And paste that here.
And just like before, since there wasn't a prod vault, yet it created it and it's got
the same four files as before, but there is one subtle difference. Let's add that new
directory to git. When I'm writing, git status, only three of the files are added.
The fourth file. The decrypt key is actually ignored from Git. You already have this
inside of Git ignore. So the pride key, we do not want to commit to the repository.
Anybody that has the prod key will be able to read all of our secrets. So on
production. The only thing that you need to worry about is somehow getting this
`prod.decrypt.private.php` file up on your server. Alternatively, you can read this
private key and set it as a special environment variable. So instead of having to
worry about lots of different environment variables, you, you can only worry about
one and, uh, point to our symfony cast screen cast about that. All right. So in the
dev environment, symfony will automatically use the dev vault in the prod
environment. It will use the prod vault. Both of these now have a GitHub_token inside
of there. The question is, how do we read secrets out of the vault?

The answer is we already are secrets, become environment variables. It's as simple as
that. So in 'config packages `framework.Yaml` by using this env syntax here, this
Github token could be a real environment variable, or it could be a secret in our
vault. All right. So to kind of see if this is working head to your mixed repository
service, And let's actually just do a `dd($this->GitHubContentClient)`, All right,
then head over and refresh. I want to see if we can find the header in there. Now
there's a really cool trick with this component. If you click on this area and then
do command or control F you can search inside of it, I'm going to search for the word
token and oh, that's not right. This is our real token, But since we're in the dev
environment, it should be reading our dev vault where we have kind of that fake
change me value Here's what's going on. Secrets become environment variables, like I
mentioned, but environ environment variables take precedence, even environment
variables that are defined in .env files. So There is there momentarily is a GitHub
token. So instead of using the one from our vault, it's using the one from our `.env
system` Here's the point.

As soon as you want to convert a value from an environment variable into a secret in
your vault, you need to fully make it a secret in your vault and not use it as an
environment variable at all anymore. In other words, delete GitHub_token In .env and
also `.env.local`. Now I'm refresh. I'll click on this again. Command F search for
token. Got it. Change me. And if we were in the prod environment, it would read the
value from the prod vault. All right, cool. So let's remove that DD And refresh to
discover that locally, everything is broken. Dang, of course, It's using that fake
token from the fake vault. So how can I fix my app locally? Could I just override the
GitHub token value Temporarily, locally, but be careful and not commit that value?
No, that's lame. Before I fix this, I'm going to show you a really handy command.
When it comes to the vault, it's `bin/console secrets:list`. This shows you your
secret values pretty cool. And you can even pass a `--reveal` to reveal the value. As
long as you have the decrypt key locally

And notice inside of here, it says what the value is. And then it says the local
value. Hmm. Let's re run the same command with `--env=prod` and same thing. Now we
can see our real prod key and you can see that there's a local value. The secret
system has a local over a way to override things just locally. And as a shortcut, you
can actually use secret set --local to do that. So I'm going to copy our real key up
here and then run `bin/console secrets set GitHub_token --local`, and then I'm going
to, you won't see it, but I paste the value and I Enter I'll copy that value. The way
you set that local value Is actually in `.env.local`. So we're actually going to say,
`github_token=` and paste that We're actually going to just locally take advantage of
the fact that environment variables override secrets. Now, if we go back over here
and run `bin/console secrets:list --reveal`, You can see that the official value and
the vault is change me, but the local value is our real token and that will, as we
know, override the secret and that will be used locally.

Okay. Team we're well, basically done. So as a reward for your hard work on these
super important, but sometimes tough topics let's celebrate using celebrate by using
symfony's code generator, library maker bundle.


# Docker & Environment Variables

We now have a Postgres database running inside of a Docker container. We can see it
by running:

```terminal
docker-compose ps
```

This also tells us that if we want to *talk* to this database, we can connect to port
`50739` on our local machine. That will be a different port for you, because it's
randomly chosen when we start Docker.

We also learned that we can talk to the database directly via:

```terminal
docker-compose exec database psql --user symfony --password app
```

To get our actual *application* to point to the database that's running on this
port, we could go into `.env` or `.env.local` and customize `DATABASE_URL`
accordingly: with user `symfony` password `ChangeMe`... and with whatever your port
currently is. Though... we *would* need to *update* that port each time we start
and stop Docker.

## Symfony Binary & Docker Env Vars

Thankfully, we don't need to do *any* of that because, surprise, the `DATABASE_URL`
environment variable is *already* being correctly set! When we set up our project,
we started a local dev server using the Symfony binary.

Just as a reminder, I'm going to run:

```terminal
symfony server:stop
```

to stop that server. And then restart it with:

```terminal
symfony serve -d
```

I'm mentioning this because the `symfony` binary has a *pretty* awesome Docker
superpower.

Watch: when you refresh now... and hover over the bottom right corner of the web
debug toolbar, it says "Env Vars: From Docker".

In short, the Symfony binary *noticed* that Docker was running and exposed some
new environment variables pointing to the database! I'll show you. Open up `public/index.php`. 

[[[ code('debfa901f6') ]]]

We don't normally care about this file... but it's a great
spot to dump some info *right* when our app starts booting. Inside the callback,
`dd()` the `$_SERVER` superglobal. That variable contains a *lot* of information,
*including* any environment variables.

Ok, spin over and refresh. Big list! Search for `DATABASE_URL` and... there it is!
But that is *not* the value that we have in our `.env` file: the port is *not*
what we have there. Nope, it's the *correct* port needed to talk to the Docker
container!

Yup, the Symfony binary detects that Docker is running and sets a *real*
`DATABASE_URL` environment variable that *points* to that container. And remember,
since this is a *real* environment variable, it will win over any value placed
in the `.env` or `.env.local` files.

The point is: *just* by starting Docker, everything is already set up: we didn't
need to touch *any* config files. That's pretty cool.

By the way, if you want to see all the environment variables the Symfony binary
is setting, you can run:

```terminal
symfony var:export --multiline
```

But the most important one by far is `DATABASE_URL`.

Ok: Doctrine is configured! Next, let's create the database itself via a `bin/console`
command. When we do that, we'll learn a trick for doing this *with* the environment
variables from the Symfony binary.

# docker-compose & Exposed Ports

We need to get a database running: MySQL, Postgresql, whatever. If you already have
one running, awesome! All you need to do is copy your `DATABASE_URL` environment
variable, open or create a `.env.local` file, paste, then change it to match whatever
your local setup is using. If you decide to do this, feel free to skip ahead to
the end of chapter 4 where we configure the `server_version`.

## Docker Just for the Database

For me, I do *not* have a database running locally on my system... and I'm *not*
going to install one. Instead, I want to use Docker. And, we're going to use Docker
in an interesting way. I *do* have PHP installed locally:

```terminal-silent
php -v
```

So I *won't* use Docker to create a container specifically for PHP. Instead I'm going
to use Docker simply to help boot up any *services* my app needs locally. And right
now, I need a database service. Thanks to some magic between Docker and the Symfony
binary, this is going to be *super* easy.

To start, remember when the Doctrine recipe asked us if we wanted Docker
configuration? Because we said yes, the recipe gave us `docker-compose.yml` and
`docker-compose.override.yml` files. When Docker boots, it will read *both* of
these... and they're split into two pieces just in case you want to *also*
use Docker to deploy to production. But we're not going to worry about that: we
just want to use Docker to make life easier for local development.

[[[ code('6190b9c37f') ]]]

[[[ code('4a4d66d50e') ]]]

These files say that they will boot a single Postgres database container
with a user called `symfony` and password `ChangeMe`:

***TIP
The username changed from `symfony` to `app` in the newest recipe version.
***

It will also expose port 5432 of the container - that's Postgres's normal port - to our
*host* machine on a *random* port. This means that we're going to be able to talk to the
Postgresql Docker container as *if* it were running on our local machine... as long as
we know the random port that Docker chose. We'll see how that works in a minute.

By the way, if you want to use MySQL instead of Postgres, you absolutely can.
Feel free to update these files... or delete both of them and run:

```terminal
php bin/console make:docker:database
```

to generate a new compose file for MySQL or MariaDB. I'm going to stick with Postgres
because it's awesome.

At this point, we're going to start Docker and learn a bit about how to communicate
with the database that lives inside. If you're pretty comfortable with Docker, feel
free to skip to the next chapter.

## Starting the Container

Anyways, let's get our container running. First, make sure you have Docker actually
installed on your machine: I won't show that because it varies by operating system.
Then, find your terminal and run:

```terminal
docker-compose up -d
```

The `-d` means "run in the background as a daemon". The first time you run this,
it'll probably download a bunch of stuff. But eventually, our container should
start!

## Communicating with the Container

Cool! But now what? How can we *talk* to the container? Run a command called:

```terminal
docker-compose ps
```

This shows info about all the containers currently running... just one for us. The
really important thing is that port 5432 in the container is connected to port
50700 on my host machine. This means that if we talk to this port, we will actually
be talking to that Postgres database. Oh, and this port is random: it'll be different
on your machine... and it'll even change each time we stop and start our container.
More on that soon.

But now that we know about port 50700, we can *use* that to connect to the database.
For example, because I'm using Postgres, I could run:

```terminal-silent
psql --user=symfony --port=50700 --host=127.0.0.1 --password app
```

That means: connect to Postgres at 127.0.0.1 port 50700 using user `symfony` and
talking to the `app` database. All of this is configured in the `docker-compose.yml`
file. Copy the `ChangeMe` password because that last flag tells Postgres to ask
for that password. Paste and... we're in!

If you're using MySQL, we can do this same thing with a `mysql` command.

But, this only works if we have that `psql` command installed on our *local*
machine. So let's try a different command. Run:

```terminal
docker-compose ps
```

again. The container is called `database`, which comes from our `docker-compose.yml`
file. So we can change the previous command to:

```terminal
docker-compose exec database psql --username symfony --password app
```

This time, we're executing the `psql` command *inside* the container, so we don't
need to install it locally. Type `ChangeMe` for the password and... we're back in!

The point is: just by running `docker-compose up`, we have a Postgres database
container that we can talk to!

## Stopping the Container

Btw, when you're ready to stop the container later, you can run:

```terminal
docker-compose stop
```

That basically turns the container off. Or you can run the more common:

```terminal
docker-compose down
```


which turns off the containers and removes them. To start back up, it's the same:

```terminal
docker-compose up -d
```

But notice that when we run `docker-compose ps` again, the port on my host machine
is a *different* random port! So, in theory, we could configure the `DATABASE_URL`
variable to point to our Postgres database, including using the correct port. But
that random port that keeps changing is going to be annoying!

Fortunately, there's a trick for this! It turns our, our app is *already* configured,
without us doing anything! That's next.

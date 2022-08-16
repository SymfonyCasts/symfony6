# The "symfony console" Command & server_version

Doctrine is now configured to talk to our database, which lives inside a Docker
container. That's thanks to the fact that the Symfony dev server exposes
this `DATABASE_URL` environment variable, which *points* to the container. For
me, the container is accessible on port 50739.

Now let's make sure the actual database has been created. But first, in `index.php`,
remove the `dd()`... then close that file.

Spin over to your terminal and run:

```terminal
php bin/console
```

This prints *every* `bin/console` command that's available *including* a bunch of
*new* ones that start with the word `doctrine`. Ooh. Most of these aren't very
important and we'll walk through the ones that *are* along the way.

## bin/console doctrine:database:create

For example, one is called `doctrine:database:create`. Cool, let's try it:

```terminal
php bin/console doctrine:database:create
```

And... error! Look closely: it's trying to connect to port 5432. But our environment
variable is pointing to port 50739! It's as if it's using the `DATABASE_URL`
value from our `.env` file instead of the *real* one that's set by the Symfony binary.

And, in fact, that's *exactly* what's happening. And, it makes sense! When we refresh
the page in our browser, that's processed *through* the `symfony` binary, which gives
it the opportunity to add the environment variable.

But when we run a `bin/console` command - where `console` is just a PHP file that
lives in a `bin/` directory, the `symfony` binary is *never* used as part of that
process. This means it never has the opportunity to add the environment variable.
And so, Symfony falls back to using the value from `.env`.

To fix this, whenever we run a `bin/console` command that needs the Docker environment
variables, instead of running `bin/console`, run `symfony console`:

```terminal-silent
symfony console doctrine:database:create
```

That's literally a shortcut to running `bin/console`: it's no different. But the
fact that we're executing it *through* the `symfony` binary gives it the opportunity
to add the environment variables.

When we try this... yes! We *do* get an error because apparently the database already
exists, but it *did* successfully connect and talk to the database.

## Configuring the server_version

Ok, there's one last bit of configuration that we need to set. Open
`config/packages/doctrine.yaml`. This file came from the recipe. Find
`server_version` and un-comment it.

[[[ code('e49193f024') ]]]

This value "13" is referring to the version of my database engine. Since I'm
using Postgres version 13, I need 13 here. If you're using MySQL, you might need
8 or 5.7.

This helps Doctrine determine which features your database does or doesn't support...
since a newer version of a database might support features that an older version
doesn't. It's not a particularly interesting piece of configuration, we just need
to make sure it's set.

Ok team: all the boring setup is *done*. Next: let's create our first entity class!
Entities are the most *foundational* concept in Doctrine and the *key* to talking
to our first database table.

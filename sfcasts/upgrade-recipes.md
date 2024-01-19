# Flex Recipe Updates

When we install packages, many of them have Flex recipes. These add new files and
sometimes modify existing files. They do everything needed so the
package works immediately. I love that!

And, over time, these recipes tend to change. Maybe they decide to add a new line
to a config file or change a default value.

Fortunately, Flex has a fancy recipe *update* system. And while you don't *need*
to update your recipes, it's a great way to keep your app looking and feeling modern.
The updates will also help fix some of the deprecation warnings we saw at the end
of the previous chapter.

Before you start, make sure you've committed any changes to `git` -
I already have - because the recipe update system *works* via Git.

To see the recipes, run:

```terminal
composer recipes
```

Cool! It looks like we have about 8 updates. So let's get to work:

```terminal
composer recipes:update
```

Updating recipes? Yea, it's one of my *favorite* things to do: it gives us a chance
to peek into what's been changing in these packages... while we've been busy, you
know, doing our real job. I'll hit enter to go down the list one-by-one.

## doctrine/doctrine-bundle Recipe Update

First up is Doctrine Bundle: and it's a complex update. It even
caused a conflict!

Sometimes we might see that a recipe update changes something - like updating a line
in a config file - but we don't really understand *why*. To help, the command lists
every pull request *behind* these changes. For example, this lazy ghosts thing...
we can click the link to see the PR and the explanation behind it.

Back in my editor, woh! I guess the conflict was in `doctrine.yaml`! Specifically,
`server_version` changed. The original recipe gave us config to work with
Postgres 13. It now ships with code for Postgres 16.

You don't need to keep the new changes. If your production database is using
Postgres 13, keep it! But I'll update to 16.

At the terminal, run:

```terminal
git status
```

Add that file to `git` to resolve it. Then see *all* the changes with:

```terminal
git diff --cached
```

Most of these are version changes: MySQL from 5.7 to 8 and Postgres from 13 to 16.
The `doctrine.yaml` config *does* have a few new lines. These are flags where
we're opting *into* some low-level change in the system. And there's a good chance
that *not* having this config would trigger a deprecation. I'll let you dig
deeper into these if you care, but they probably won't affect anything.

`docker-compose.yaml` contains more changes that go from Postgres 13 to 16. So again,
you can keep these or get rid of them.

And then, lurking at the bottom, `symfony.lock` keeps track of which version of the recipe
we have installed. So, we're good! Commit these changes... and use a better commit
message than I am.

To use the new version of Postgres from `docker-compose.yaml`, run:

```terminal
docker compose down
```

Then

```terminal
docker compose up -d
```

We now have Postgres 16 running. Watch: the homepage still works because it
doesn't talk to the database. But when we click "browse mixes", broken! An
undefined table because we're using a fresh database. Fix that by running:

```terminal
symfony console doctrine:migrations:migrate
```

Cool. And:

```terminal
symfony console doctrine:fixtures:load
```

Double cool. Now... we're good!

## doctrine/doctrine-migrations-bundle Recipe Update

Back to the terminal... and back to work:

```terminal
composer recipes:update
```

On deck is `doctrine-migrations-bundle`. This is minor. The bundle comes with
a profiler integration: it's this little icon on the web debug toolbar. It's not
super useful... and so it's changed to be *not* enabled by default. Let's commit
that... and update the next one.

```terminal-silent
composer recipes:update
```

## symfony/framework-bundle Recipe Update

Framework bundle! The *core* of Symfony! Run `git diff --cached` to see the
changes. Like Doctrine, most of these are low level where we opt into a
new behavior. For example, annotations are deprecated, so we're turning them off.
`handle_all_throwables` means that Symfony will transform exceptions into error
pages but *also* other types of errors. And `storage_factory_id` was removed
because that's the default value.

Easy! Commit that... then keep going:

```terminal-silent
composer recipes:update
```

## symfony/monolog-bundle Recipe Update

Next up is monolog-bundle. The only change is a new `formatter` key at the end
of `monolog.yaml`. This is a consistency change. Down here in the `prod`
config, the main log handler already has this `formatter` key. It was added
under `deprecations` so that everything is formatted the same. Minor,
but nice! We'll talk more about this deprecation log soon.

So, commit! And...

```terminal-silent
composer recipes:update
```

## symfony/routing Recipe Update

Routing. Dead simple. The code that imports the `#[Route]` attributes,
apparently, needs a `namespace` key. Whatever.

## symfony/translation Recipe update

Commit... and onto

```terminal-silent
composer recipes:update
```

symfony/translation. Another easy one: `translation.yaml` used to have
some commented-out providers as an example... and now they're gone. But if you install
one of these provider packages, *its* recipe will re-add the line.

Commit that... and we're down to the final 2 recipes! These are both related to
changes with Webpack Encore and a new StimulusBundle. That deserves its own
chapter, so let's do it next!

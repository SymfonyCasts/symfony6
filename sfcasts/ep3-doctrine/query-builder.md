# The Query Builder

The `/browse` page is working... but what if we click on one of these genres? Well...
it *kind of* works. It shows the *name* of the genre... but we always get a list
of *all* the mixes. What we *really* want is to filter these to only show the mixes
for *that* specific genre.

Right now, every mix in the database is in the "Pop" genre. So to make our data a
bit more interesting, head back into `MixController`, find the fake method
that creates new mixes, and add a `$genres` variable with "Pop" *and* "Rock"
included. Then select a random with with `$genres[array_rand($genres)]`.

Cool! Now go to `/mix/new` and refresh a few times... until we have about 15
mixes. Back on `/browse`... yup! We have a mix of "Rock" and "Pop" genres... they
just don't *filter* yet.

So our mission is clear: to customize the database query to *only* return the results
for a specific genre? Ok, we can actually do that super easily in `VinylController`
via the `findBy()` method. The genre is in the URL as the `$slug` wildcard.

So we *could* add an "if" statement where if there *is* a genre, we return all
the results where `genre` matches `$slug`. *But* this is a *great* opportunity to
learn how to create a custom query. So let me undo that.

## Custom Repository Method

The best way to do a custom query, is to create a new method in the *repository*
class for whatever entity you're fetching data for. In this case, that means
`VinylMixRepository`. This are holds a few example methods. Let's un-comment
the first... and then start *simple*. Call it `findAllOrderedByVotes()`. We won't
worry about the genre quite yet, I just want to make a query that returns all of
the mixes ordered by votes. Remove the argument, this will return an array and the
PHPdoc above helps my editor know that this will be an array of `VinylMix` objects

## DQL and the QueryBuilder

There are a few different ways to execute a custom query in Doctrine. Doctrine,
of course, eventually makes SQL queries. But because Doctrine works with MySQL,
Postgres and other database engines... all the SQL needed for each of those will
look a little different.

To handle this, internally, Doctrine has its own query language called Doctrine Query
Language or "DQL", which looks something like:

> SELECT v FROM App\Entity\VinylMix v WHERE v.genre = 'pop';

You *can* write these strings by hand. But usually I leverage Doctrine's
"QueryBuilder": a nice object that helps *build* this string step-by-step.

## Creating the QueryBuilder

Let's do that. Start with `$this->createQueryBuilder()` and pass this an *alias*
for this class. This could be anything... but let's say `mix`.

Because we're calling this from inside of `VinylMixRepository`, the QueryBuilder
already knows to query from the `VinylMix` entity using "mix" as the alias. If
we executed this query builder right now, it would basically be:

> SELECT * FROM vinyl_mix AS mix

The query builder is *loaded* with methods to control the query. For example,
call `->orderBy()` and pass this `mix` - since that's our alias - `.votes` then
`DESC`.

Done! Now that our query is built, to execute it we always call `->getQuery()` (that
turns it into a `Query` object) and then `->getResult()`.

Well actually, there are a number of methods you can call to get the results.
The main two are `getResult()` - which returns an *array* of the matching objects -
or `getOneOrNullResult()`, which is what you would use if you were querying for
one *specific* `VinylMix`. Because we want to return an array of matching mixes,
we're using `getResult()`.

Ok, let's try that! Over in `VinylController` (let me close `MixController`...),
instead of `findBy()`, call `findAllOrderedByVotes()`.

I *love* how clear that method is: it makes it super obvious exactly *what*
we're querying for. And when we try it... it still works! It's not filtering yet,
but the order *is* correct.

## Adding the WHERE Statement

Okay, back to our new method. Add an optional `string $genre = null` argument.
*If* a genre is passed, we need to add a "where" statement. I'm going to break this
onto multiple lines... and then replace `return` with `$queryBuilder =`. Below,
say `return $queryBuilder` with `->getQuery()`, and `->getResult()`.

*Now* we can say `if ($genre)`, and add the "where" statement. How? Easy!
`$queryBuilder->andWhere()`.

Oh, but a word of warning. There is *also* a `where()` method... but I *never* use
that. When you call `where()`, it will *clear* any existing "where" statements
that the query builder might have... so you might accidentally *remove* something
you added earlier. So, *always* use `andWhere()`. Doctrine is smart enough to figure
out that, because this is the *first* WHERE, it doesn't actually need to add the
`AND`.

Inside of `andWhere()`, pass `mix.genre =`... but *don't* actually put the genre
right in the string. That is a *huge* no-no. Never *ever* do that. That opens you
up for SQL injection attacks... which is *bad news*. Instead, whenever you need
to put a dynamic value into a query, use a "prepared statement"... which is a fancy
way of saying that you put a placeholder here, like `:genre`. The name of this could
be *anything*... you could call it "dinosaur" if you want. But *whatever* you put
here, you'll then fill *in* the placeholder by saying `->setParameter()` with the
*name* of the parameter - so `genre` - and then whatever value is - in our case,
`$genre`.

Beautiful! Back over in `VinylController`, pass `$slug` as the genre.

Okay, let's try this! Click back to the `/browse` page first. Awesome! We get all
the results. Now click "Rock" and... nice! Less results, all genres show "Rock"!
If I filter by "Pop"... got it! We can even see the query for this... here it is.
It has the "where" statement for genre, equaling "Pop". Woo!

## Reusing Query Builder Logic

As your project gets bigger and bigger, you're going to create more and more methods
in your repository for custom queries. And you may start repeating the same query
logic over and over again. For example, we might eventually order by the votes in
a *bunch* of different methods in this class.

To avoid duplication, we can isolate that logic into a private method. Check oit
out! Add `private function addOrderByVotesQueryBuilder()`. This will accept a
`QueryBuilder` argument (we want the one from `Doctrine\ORM`), but let's make it
*optional*. And we will also *return* a `QueryBuilder`.

The job of this method is to add this `->orderBy()` line. And for convenience,
if we don't pass in a `$queryBuilder`, we'll create a new one.

To handle that, start by saying
`$queryBuilder = $queryBuilder ?? $this->createQueryBuilder('mix')`. I'm
purposely using `mix` again for the alias. To keep life simple, choose an alias
for an entity and *consistently* use it everywhere.

Anyways, this line itself may look weird, but it basically says:

> If there *is* a QueryBuilder, then just use it. Else, create a new one.

Below `return $queryBuilder`... and then go steal the `->orderBy()` logic from up
here and... paste. Awesome!

PhpStorm is a little angry with me... but that's just because PhpStorm is having
a rough morning and needs a restart: our code is, hopefully, just fine.


Back up in the original method, now we can simplify to
`$queryBuilder = $this->addOrderByVotesQueryBuilder()` pass it *nothing*. *It* will
create that QueryBuilder and add the `orderBy()`.

When we refresh... it *still* works.

Next, let's add a "mix show" page where we can view a *single* vinyl mix. For the
first time, we'll query for a single object from the database and deal with what
happens if *no* matching mix is found.

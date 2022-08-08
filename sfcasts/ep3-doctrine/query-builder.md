# Query Builder

Coming soon...

The browse page is working, but what if we click on one of these genres? Well, it
kind of works. It shows the name of the genre, but no matter what we click, we just
get all of the mixes. What we really want is to filter these mixes, only show the
ones for the matching genre. And right now they're all the pop genre. So to make our
data a little bit more interesting, head back into mix controller into our fake
method that creates new mixes, let's create a genres variable with pop and rock in
it. We'll start by just, we'll start just by just randomly choosing one of those
genres. And then we'll say genres and we can use use array Rand and pass it genres.
Okay. Except except that every ran of course is a function. That's better. Cool. So
now if we go to mix /new, I'm going to refresh this a few times. So we get about 15
mixes and then back on the browse page, yes. Go. We have a mix of some rock and some
pop in here, but it still doesn't actually filter correctly. All right. So how can we
make a, how can we customize the query to only return the results for a certain
genre?

Well, we can actually do that in vinyl controller via this fined by method. The genre
is in, in the URL as this slug wild card. And so we could use this slug argument
here. If it's passed, we could add an if statement so that we could query where slug,
whereas genre matches the slug, but this is a great opportunity to see how to create
custom queries. So let me undo that whenever you want to create a custom query,
you're going to go into the repository for that. So is a query for the vinyl mix
entity. We're going to open up a vinyl mix repository, and there are already a few
example methods in here with custom queries. Let's uncommon the first one, and then
start real simple. I'm going to say, find all ordered by votes. So we're not going to
worry about the genre at all yet. I just want to make a query that returns all of the
mixes ordered by the, the votes. And I'll remove that argument. This is going to
return an array and the PHP tion up here helps it know that it's going to be an array
of vinyl mix objects. Now, there are a few different ways to create a custom query
and doctrine, but

Doctrine of course eventually makes SQL queries, but doctrine works with my SQL and
Postgres SQL and all the SQL for those look a little bit different. So internally
doctrine is its own query language, which is very, which you'll feel very familiar
called doctrine query language or DQ. L if you were to see DQ L as a string, it would
look something like select star from vinyl mix. I don't know if I'll even show an
example, so you can actually write this string DQ L by hand, but more commonly you're
going to create something called a query builder, which is this nice object that just
helps you build a query step by step. So let's do that. Why don't we start with this
era create query builder. And then this first argument here is going to be the alias.
So I'm just going to say, I'm going to, this is going to be anything, but let's say
mix. So this will effectively be since we're in the vinyl mix repository, it knows
we're going to CR query from the vinyl mix table. So this is basically like select
from vinyl_mix as mix. And then one of the methods we can call here is forward. Or by

Then we can fax this mix since that's our alias.votes. And then we'll say descending,
and that's it. And it's actually create to actually execute the query. Whenever we're
done with the query builder, we always call get query that returns it into a query
object and then get result. There are actually a number of methods you can call in
here to get the results. The main two that you need to know about are get results.
This will return an array of results or get one or no result. And this is what you
would use if you were querying for one specific vinyl mix. So it would return that
one object or no, in our case, we want to return the array of results. So we'll use
get result. All right, let's try that over in mix controller, or is actually vinyl
controller, close mix controller, instead of fine by we're going to call find all
ordered by votes and immediately I love how clear that method is. I can see exactly
what results, what I'm querying for, and when we try it, it still works. It's not
filtering yet, but you can see the order is correct.

All right. Now, in on our new method, let's add an optional string genre argument. So
say string genre = Nu. And then if the genres passed, we're going to add a where
statement. So to let me do this, I'm going to actually break this statement onto
multiple lines. So first I'm say query builder = and we'll create the query builder,
and we'll add the order by, but then we'll stop right there. Then at the bottom,
we'll say return query, builder,-> get query-> get resolved. So, so far this makes no
change just breaking this onto multiple lines. But now we can say if genre, and we'll
add that, where statement, how do we do that? It's a method on our query builder say
query builder,-> and where now one word of warning. There is also a where method. I
never use that method. When you call wear, it will clear out any other wear
statements that you might have. So you might accidentally remove a wear statement
that you added earlier, always use and wear. And even though we don't have a wear
statement, yet doctrine is smart enough, not is smart enough to figure that out and
not cause an error. So inside of the end, where what we pass here is mix.genre

Equals, but we don't actually try to put the genre in right here. That is a huge, no,
no never, ever, ever do that. That opens you up for SQL injection attacks. Instead,
whenever you are putting a dynamic value into a query, you always use a prepared
statement, which is a fancy way of saying that you put a little placeholder here,
like:genre, and the name of this could be anything could be dinosaur if you want, but
whatever you put there, you then fill in that, uh, placeholder by calling saying, set
parameter the name of the parameter. So genre, and then whatever value that should
put here. So our dynamic genre, beautiful. All right, over in vinyl controller. Now
we can pass in our slug genre. All right. So let's try this. I'm going to click back
to the browse page first. Awesome. We get all the results. Now we'll click rock and
beautiful, less results, all rock results by pop. Awesome. And we can even see the
query for this if we want to. And you can see there is the query there, and it's got
the wear statement for the genre, equaling pop. Awesome.

Now, as your project gets bigger and bigger, you're going to create more and more
custom methods inside here for custom queries. And you may find that you start
repeating the same query logic over and over again. For example, we might eventually
order by the votes in a bunch of different methods inside of this repository to avoid
duplication. We can isolate this into a private method. So check this out. We can say
something like private function, add order by votes, query builder. And then this
will accept a query builder argument. We want the one from doctrine /ORM, but let's
make this argument optional. And this method itself will return a query builder. So
the idea is that the job of this method will be to basically add this order by line
there, we can pass it a query builder that it will modify, or if we don't, it will
just create a fresh1. So we can start here by saying query builder, question,
question, this-> create query query builder. And we want to use that same mix. Um,
AALS every time we create a query builder. So this might look a little funny, but
this basically says if there is a query builder, then just use it else, create a
fresh1.

And then down here, we'll say return query builder. And then I'm going to go steal
this order by logic from up there and paste. Awesome.

And you can see that Peter services like she a little bit mad at me right now. This
is actually just because my storm is airing out. I need to restart it. So if you're a
little confused by pizza storm here, that's, what's going on anyways, up here. Now we
can simply say, query builder = this-> add order by votes, builder, and we'll pass it
nothing. And it will create that builder for us and add the order by, and now when we
refresh that still works next, let's add a mix show page where we can view a single
vinyl mix for the first time we'll query for a single object from the database and
deal with what happens if no matching mix is found.


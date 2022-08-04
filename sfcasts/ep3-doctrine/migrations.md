# Migrations

Coming soon...

We've created our entity class. Yay. But that's it, the corresponding table does not
yet exist in our database. In theory, doctrine knows about our entity and it knows
about all the columns. So shouldn't it be able to make that table automatically for
us? Yes, and it can't when we installed doctrine earlier, it came with a migrations
library that is amazing. Check this out. Whenever you make a change to your database
structure, like adding a new entity class, or even adding a new property to an
existing entity class, you can spin over it and run Symfony console, make migration.
In this case, I'm running Symfony console because this is going to talk to my
database show you run that and perfect. It creates one new file in a migrations
directory with a timestamp, uh, for today's date. Let's go check that out migrations.
And then this version file inside has an up and a down method. Technically you can
migrate down. I never do so let's just focus on the up. This is awesome. The
migrations command saw our vinyl mix entity realized that its table was missing and
so generated the SQL needed in Postgres to create it, including all of the columns

That is awesome and dead simple. How do we actually execute this migration, spin back
over and run it. Symfony console doctrine migrations migrate. Okay. Yes. To confirm
and beautiful. It says migrating up to that specific version. So I think that that
worked to see if it did, you can connect to your database, however you want, or you
can use another Hansey bin consult man say Symfony consult doctrine query S SQL. And
we can do here is we can say select star from vinyl_mix, because I told you it should
create a vinyl_mix table. And when we do, whoops, let me try that again with the
single quotes in the end. Perfect. So we didn't get an air. It just said the query
yielded an empty result set at that table had not existed like vinyl Fu we would've
gotten an air about the table, not existing. So that did create the table. Now here's
the way that migration system works behind the scenes. You'll notice if I run Symfony
console doctrine, migrations migrate again, it is smart enough to not try to RECU
this migration. It knows that it already executed this. And so doesn't execute it
again the way it does that becomes a little more obvious. If you run Symfony console
doctrine, migrations status,

This gives some general information about the SQL system. The most important thing
here is this storage where it says table name, doctrine migration versions. So the
first time that we executed that migration doctrine created this special table behind
the scenes, which literally just stores a list. All of, all of the migration classes
that it's executed. So every time we run doctor migrations migrate, it's going to
look in our migrations directory for all the classes inside of here, but it's only
going to execute the new ones that it hasn't executed before. Another way to
visualize this is to run Dr. Migration's list. And this gives you a status of all of
the migration. So CSR one migration, and it knows it already ran it and it even has
the date. All right, so this is cool, but let's kind of do something a little more
interesting in our vinyl mix entity. One property I forgot to add was votes. We're
going to keep track of the number of VO up votes or down votes that a particular mix
has. So what we want to do is add a pro is modify this class to add a new property,
and you can actually do that with the same bin console, make S to command. This can
be used to create entities, but also do update entities. So I'll type vinyl mix is
the class name. It sees that my S entity already exists.

And then let's add votes. All of this, be an in type, not know, and then hit enter to
finish. And the end result of that is very simply that it added this new property
right here. And it also added the corresponding getter and setter methods down below.
Now, in this situation, we know that we do have a vinyl mix table in the database,
but this vinyl mix table is going to be missing this new votes column. We need to
modify that table to add that. How do we do that? The exact same way at your terminal
run Symfony console, make:migration.

Then go check out that class. This is amazing. If you look in the up method, it says
alter table vinyl mix add votes. So it looked at our vinyl mixed S entity looked in
the database at the vinyl mix table and generated the diff between them. It realized
that in order to make the database look like our S entity, it needed to alter table
to add that vote's column doctrine is smart. Now over on our plan line, if you run
Symfony consult doctrine, migrations list, you'll see that it recognizes both
migrations and it can see that it hasn't migrated the second one.

So run Dr. Migraine. He's migrate. It's going to be smart enough to skip the first
one and execute the second one. And now we are good. So what this means is that every
time you deploy each time you're deploy, you're going to, you should run this Dr.
Migrations migrate command, and it will handle executing any and all migrations that
the production database hasn't executed yet. Now, one more quick thing that I want to
do while we're here inside of our vinyl mix entity, you can see the new votes
property and it defaults to no. What makes a lot of sense, probably with the new
vinyl mix to default its votes to zero. So I'm going to change this null to zero. And
if I do that, I don't need to make this property nullable anymore because this has
nothing to do with doctrine. This is just PHP code. If I have a property that's
initialized to an integer, then it's going to be an int at all times.

It's never going to be null. Now, the question I have is did that change because I
made that change. Do I need to alter anything in my database? The answer is no. And
the way you'd see this is by running a helpful plan called doc Sony console doctrine,
schema update, dump SQL. This is very similar to the make migration command, but
instead of generating a file with the SQL, it will just dump whatever SQL is needed
to bring your database up to date. And in this case, it can show you that your
database is already in sync with your entity.

So change initializing the value. My point is, if you change the, if you initialize
the value in PHP, this is just a PHP change. It doesn't change how the column is
store in the database. Let's actually do one other change. We have this created at
field here. It'd be awesome. If that would be set automatically, when we create a new
vinyl mix object, instead of us needing to manually set that, and we can do that by
creating a construct method, good old fashioned PHP construct method inside here,
I'll say this->created that = new date, time mutable, which of course defaults to
right now. Then we don't need the = no up here anymore. Since it's going to be
initialized down here, we also don't need the question, mark, cuz it's always going
to be a datetime immutable object. So that makes our types a little bit tighter. And
also it's cool, man, because that created up property is going to be set
automatically every time that we initialize our instant or object and no surprise
that change doesn't require, that's just a PHP change. It doesn't change how the data
is stored in the table at all. All right, so we have a vinyl mix entity. We have the
corresponding table in the database next let's instantiate a vinyl mix object and
save it to the database.

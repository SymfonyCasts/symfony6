# Timestampable

Coming soon...

I really like adding timetable behavior to my entities. That's where you have created
at and updated at properties that are automatically set, just helps to keep track of
when things happened. We added a created at cleverly by hand by sending it in a
construction. But what about updated that? Well, Dr. Has an awesome event system and
we could hook into that to automatically run code on update that sets an updated at
property, but there's a library that already does that let's get installed at your
terminal run composer require stop /doctrine extensions, bundle this installs a small
bundle. That's a wrapper around a library called doctrine extensions. This executes
this also like lots of libraries. This installs a repository, but this is the first
one we've installed. That's from the RI repos. Remember Symfony actually has two
repositories for its recipes. There's the main Symfony repositories. This is closely
guarded by the Symfony core team. There's also another one called recipes can trip.
There are some quality checks on this repository, but it's a lot easier to get
recipes in there. So the first time that Symfony installs a recipe thing can trip
repository. It just kind of, it tells you about that and it asks you if that's okay,
I'm going to say P for yes, permanently,

And we're going to run get status. Awesome. You can see this added a new bundle and
it also added a new configuration file that will look lack in a second. So this
bundle obviously has its own documentation. You can search for soft doctrine,
extensions, bundle and find some documentation on c.com. But the majority of the
documentation is actually on the underlying doctrine. Library gives us the, the
ability to add a bunch of really cool behaviors like S slugable and timetable. All
right, so let's add timetable first. So step one, do this is to go into config
packages and open that new configuration file. It just added here. Add ORM because
we're using doctrine, om, then defaults because we're using and then timetable true.
Now this doesn't do anything yet. It just activates a doctrine listener that will now
be looking for entities that support timetable each time an entity is inserted or
updated. How do we make our vinyl mix support timetable the easiest way? And the way
I like to do it is via a trait. If the top of class say, use timetable entity, that's
it. Now the real magic is inside of this. So hold commander control and click into
that timetable entity. What this does is add two properties, created that and updated
that. Now these are just norm. These are just normal columns, like the created at
that we had,

And it's also get our set methods down here, just like we have in our RealD. The real
magic here is this get mode timetable attribute this activates that this property
should be set on update and this property should be set on create. So thanks to this
tray, we don't actually have to put any of that code. We can just use that trait
it'll work, but now we are created at, we don't even need our created at anymore, cuz
that's already in the tray. So I'll delete property and then search. I can also
delete the instructor and search down here and delete the getter and set method. All
of that is inside of the trait. All right, cool. Now the trait has a created that
like we already had, but it also adds an update that proper, uh, column. So we need
to, to create a new migration for that at your terminal, run it, Symfony console,
make:migration. Awesome. That generated the file. And I always like to just go check
on that file to make sure it looks like I expect let's see here. Awesome alter table
vinyl mix, add updated that. And then you can see it's actually altering the created
that slightly

Beautiful. All right, let's run that Symfony console doctrine, migrations migrate and
it fails column updated that of relation vinyl mix contains no values. This is a not
no violation that makes sense. Our database already has a bunch of records in it. And
so when we try to add a new updated that column, that doesn't allow Noel values,

It freaks out because there's now because that would cause a bunch of cuz all
existing rows don't have a value for that. Now if the current state of our database
was already on production, we would need to tweak this migration to give the new
column a default value for those existing records. You can learn more about that on
our other tutorial, but since that's not the case, we don't have anything on
production yet we can just drop our database and start over without any records in
there to do that. Run Symfony council doctrine, database drop-dash force to
completely drop our database. Then we can recreate the database with doctrine,
database create. And then this point we have an empty database with no tables. We can
rerun all of our migrations. So in this case, because I dropped the database
entirely, that also dropped that migrations versions table as well. So this will
execute all of our migrations from the very beginning. So you can see three
migrations executed in that time. It worked now back up our site. If we browse, we
don't have any mixes cause we just cleared our own database. So let's go to /mix
/new, that creates mix ID one. I'll refresh a few more times

And then let's take, go to mix /seven. And I'm also going to upload that. All right,
let's see if that worked. So I'm going to kind of sheet and use Symfony console
doctrine, query SQL select star from vinyl mix where ID = seven and awesome. Check
this out. The created ad is set and then the update of that is set for just a few
seconds later when we did the up vote, it works. We can now easily add a timetable to
any new entity to the future just by adding that trait. All right, next, let's
leverage another behavior. S slugable that's going to allow us to have nicer URLs
that don't have the ID in them.

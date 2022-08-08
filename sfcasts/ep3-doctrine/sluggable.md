# Sluggable

Coming soon...

Using a database ID in your URLs is just kind of lame. Instead, you typically see
slugs. These are URL, safe versions of the name of the item. In this case, the name
of our mix to support this. All our vinyl mix entity needs is a slug property that
holds that URL safe string. Then it's super easy to query for that. The only trick is
that something needs to look at the mixes title and set that slug property. Whenever
a mix is saved, that's the job of the S slugable behavior. First go back to config,
packages, stuff, document extensions,.YAML, and adds. Slugable true. Once again, that
enables a listener that will be looking at our entities whenever they save looking to
see if the slugable behavior is activated before we activate the slugable behavior.
The first thing we need to do is actually grade eight, property called slug. So run
over, move, move over to your terminal and ma and run. `make:entity` let's update
vinyl mix to add any slug field. This will be a string and let's have it be just a
hundred long. Having a string in our Ural. That's 255 characters long, probably
doesn't make sense

And make it not null. So it's required in the database and that's it hit enter one
more time to finish that, not surprisingly added a slug property here with a get slug
and set slug methods at the bottom. Now, one other thing that the command one, one
thing that the command make into the command doesn't ask you is whether or not you
want a property to be unique in the database. In the case of the slug, we do want it
to be unique. So add unique. True. Now we'll add a unique constraint in the database
to make sure that we don't ever get duplicates for this. Okay? So we have a new
column. We have a new field in the property. We need a migration to add that column
to the database. We know how to do that. Find your terminal run simp console, make
migration as usual. I'll open up that new migration file to make sure it looks right
and perfect. It adds the slug. And then it also adds the unique index on slug. And
when we run it with Symfony console doctrine, migrations migrate, it explodes again
for the same reason as last time, not Nu violation, adding a new slug column to our

Entity, to our table. That is not null, which means that any existing records can't
really work. So, as I said before, if your database was already in production, you
would need to fix this. But since ours isn't, we're just going to reset the database
like we did last time, Symfony console doctrine, database drop-dash force, then
recreate the database and then we'll rerun all of the migrations from the very
beginning four migrations executed. Perfect. So at this point we have activated the S
slugable listener. We have added a SLU column, but we're still missing a step. Like I
prove it by going to /mix /and error column slug violates the not no constraint. So
nothing is setting that slug property yet to tell the stock doctrine extensions
library, that this is a slug property that should automatically be set. We add
surprise and attribute. It's called slug hit tab to auto complete that, which will
add the use statement that you need on top. And then I'll, then we need to say
fields,

Which

Is set to an array in inside, just title. So this says, use the title field to
generate this slug. And now looks like it's working. If we check the database Symfony
console, doctor inquiry SQL all SL star from vinyl mix. Yep. Awesome. Down here is
the slug and you can see the libraries even smart enough to add a
little-one-two-three, to keep it unique. Now that we have this slug column over a mix
controller, we can change our show action to use slug here instead. And that's
actually all we need to do here because we're calling this slug. It's not going to
use the slug property on vinyl mix to do the query. The other important thing we need
to do is make sure that we update any, uh,

Update our code for any, any time we generate a URL to this route. For example, if I
copy app mix, show and search inside this file, we use it down here after we vote. We
redirect. So now instead of passing an ID while card need to pass slug set to get
slug. And if you search there's one other partner code that we need to update it's
templates, vinyl, browse, age, Tim twig. Here it is right here. The link on the
browse page, change slug to mix do slug. All. Let's try it. Let me refresh a few more
times here. We'll head back to the homepage, click browse. Awesome. There's a list
and click one of 'em beautiful. Use this log it queried via this log. Life is good.
All right. So right now to get dummy data to play with, we've created this new
action, but that's a pretty poor way to add dummy data. It's pretty manual. I have to
go refresh the page a bunch of times and the data's a little bit random, but not
super interesting. So next let's add a proper data. Fix your system to fix this.


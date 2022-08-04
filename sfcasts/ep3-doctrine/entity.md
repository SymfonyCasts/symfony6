# Entity Class

Coming soon...

One of the coolest, but maybe most surprising things about doctrine is that doctrine
basically wants you to pretend like the database doesn't exist. It's actually really
cool. Instead of thinking about tables and columns, all we need to do is think about
objects and properties. For example, let's say that we want to save some product
data. The way you do that in doctrine is by creating a product class with some
properties

That hold the data, then you create that property object set the data onto it. And
then just nicely ask doctrine to save that for us, we don't have to worry about how
doctrine does that. Now behind the scenes doctrine will save that object to a table
where each property is mapped to a column. This is called doctor does an object,
relational mapper, or an R M, which literally means that it maps the data on an
object to a row on a table. Then later, when you want to get that data back, you
don't think about querying that table in its columns. Instead, you just ask doctor to
find the object that you had earlier. Of course, behind the scenes, it will query
that table to get all of the data, but then it will recreate the object about that
data and give us the object. So while we had to do was deal with classes and objects,
ind doctrine took care of all of the saving and querying automatically for us. So
one, this means that if we ever need to save something to the database with doctrine,
we need to create a special class to model the, that thing like a product class.
These classes are indoctrine. These classes that are able to be saved. The database
are given a special name entities though. They're really just normal PHP classes and
you can create them by hand, but there's a maker bundle command that makes us much
nicer

Spin over return terminal and run bin console, `make:entity`. We don't have to run
Symfony console, `make:entity` in this case because this command doesn't need to talk
to the database. It just generates code. All right. So we want to create a class
where we're able to save all of the vinyl mixes in our system. So let's create a new
class called vinyl mix, then click no for this broadcasting updates. That's an extra
thing related to Symfony to turbo. All right, it then asks what properties we want.
So we can interactively start adding properties to our class. So we're going to add
several, I'm going to start with1 called title. Next. It's going to ask for what type
of field this is, and it's kind of referring to a doctrine type and you can hit
question mark to show you all of the different doctrine types. So the basic types up
here like string and text text can hold more. Uh, text can hold more than a string
boolean is your float, their relationship fields. We'll talk about in a future
tutorial and a special couple extra fields like storing JSUN, uh, or `DateTime`
fields. So in this case, I'm going to use a string field,

Which can hold up to 255, 5 characters, and I will use the default 255 length then
asks, can this field be null in the database? So I'm going to answer no, which means
it will be not null, or it will be required in the database. Perfect, done. Now it
asks us for another property and we can interactively add as many properties as we
want. So let's add a few more, let I need description. We'll have this be that text
type, which can store more text than the string. And let's say yes to nullable, which
will make this an optional field in the database done. Next, next property. Let's
call it track count. This will be an integer type and we will have this be not null.
Then genre, will this be a Stringfield, 2 55 length and also not null so that it's
required in the database. Finally, let's add a created at field so we configure out
when these vinyl mixes were originally created this time because the field name ends
an at sounds like a date field. The command suggests is date time immutable. So I'll
hit enter to use that. And we will also make this not know in the database

And this time we don't have any more properties to add. Right now we can add some
more later. So hit enter one more time to exit the command. Now to be clear, this did
not talk to or interact with our database at all. All this command did was generate
two classes. The first isn't source entity, the first is source entity vinyl mix.
Next one is source repository, vinyl mix repository. Ignore the repository one for
now. We'll talk about that in a few minutes, open up the vinyl mix entity. Now for
the most part, this is a normal, boring PHP class. You can see that it created a
private property for all of the different fields that we added, including an extra
private ID property. It also added a getter and setter method for, for each of those
private properties. So it's basically just a class that holds data and we can read
and we can access and set that data via the getter and setter methods. The only thing
that makes this class special at all are these annotations. You see? So by adding
this ORM /entity above the class, this is what tells doctrine. I want to be able to
save this

Objects of this class, to the database. This is an entity

Then above each property. We have an ORM /column, which tells doctrine that we want
to save this property as a column in the table. This also communicates other things
like the length that that column should be and whether or not it should be nullable
in the database. nullable false is the default. So you can see that it only generated
nullable true on the one column where we needed that value. The other thing, the ORM
/column controls is the type of field that that will be in the database. Like, is it
a string field or a date time field, or a blob field? And that's controlled via this
type option in which, and this doesn't refer directly to like a MyQ or post grads
type. This is kind of like a doctrine typing system. So there's a text type in
doctrine. Now you'll notice that only the, that there's this, that this type option
only shows up on the description field. The reason for that is really cool. Doctrine
is smart. It's able to look at the property type, the type on your property and guess
the type from it. So when you have a string property type

Doctrine assumes that you want that to be its string type.

So you could put type, you could put this type string above this, in this ORM
/column, but it's not but needed because doctrine is already guessing that we did
need it. Do need it below here though, because in this case, we don't want to use the
string type. We want to use the text type, but in every other situation, it works
like this int property type tells it to use, uh, guesses, the correct type from that.
And then same thing down here for the `DateTime` immutable that tells it that it
should be a datetime immutable type. So for the most part, we don't even have to tell
doctor the type, it figures it out automatically. And in addition to controlling
things about each column, we can also control the name of this table if we want to,
by adding an ORM /table above this, with name set to for example, vinyl_mix, but we
don't even need that because doctrine is really good at guessing that by default, it
takes your class names and turns them into lower camel case. So even without this om
/table, that is going to be the name that this table saves to. Same thing with your,
uh, properties track count here, one up being track_count. So it just takes care of
all of that for you. You don't need to think about your table name or your column
names.

Okay? So at this point we've run that command and it generated this class for us.
Yay, but we don't actually have a vinyl mix_mix table in our database yet with all of
these columns, how do we create one with a database migration? That's next.

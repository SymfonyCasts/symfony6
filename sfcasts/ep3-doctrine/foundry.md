# Foundry

Coming soon...

Building fixtures is pretty simple, but kind of boring. It would be super boring to
create 25 mixes inside this load method. So that's why we're going to install an
awesome library. Call Foundry to install, run composer require Zen struck
/Foundry-dash dev using-dev because this is a tool you just need for when you're
developing or in your tests. When the finishes, you can run, get status to see that
this enabled the bundle and also created a single one configuration file, which we
probably won't need to look at all right, to see what Foundry Foundry helps us create
objects, but to see what it really does, let's jump in and use it first for each
entity in your project. Right now we only have one you'll need a corresponding
factory class. You can create that by running bin console, make factory, which is a
maker command that comes from the Foundry library. And then you can select which
entity class you want to create for. Or you can select for all of 'em if you have
multiple entities. So we'll generate the factory for vinyl mix, and that created a
single file vinyl mix factory. Let's go check that out. Source factory vinyl mix
factory.

This class about this class, you can see a bunch of methods being described. This
class is really good at creating and saving vinyl mix objects or creating many of
them or finding a random one or a random set or a random range. So the only thing
that's really inside of here is this get defaults area which returns some default
data. Whenever a vinyl mix is created. We'll talk more about this in a second, but to
use this class in app fixtures, I'm going to delete everything inside of here. And we
can say vinyl mix factory, and we're going to call a static method on it called
create one. That's it now spin over reloader fixtures, Symfony console doctrine,
fixtures load, and it fails expected argument type date, time null given. So it says
something try to call set, created that on vinyl mix and instead of passing at the
date time, it passed at Nu. So what's happening here is inside of our vinyl mix
entity. If we look for, if you scroll up and open the timestamp entity, we have a set
created that method in here. Oh, I weren't going to past it. That expect expects a
`DateTime` object, something called this method and past it null. Instead, this
actually helps show off how Foundry works.

When we call vinyl, XFactor create one, it creates a new vinyl mix and then sets all
of this data onto it. Now, remember all of these properties are private, so it
doesn't set the title property directly. It calls these set title method and these
set track count method down here for created that and updated that it called set,
created that and passed it. No ran in reality, we do not need to actually set those
two properties at all because they're automatically set. So if we try this, now it
works. And if we go and check out our sites, it's awesome. You can see it has 928,000
tracks. It has a random title and it has 301 votes. All of this is coming from the
get defaults method. Foundry leverages another library called faker. Who's only job
is to help create fake data. So if you want some fake text, you could say self
calling, faker, a text or a random number. There are many, many different methods
that you can call on faker to get all kinds of fake data, super handy. So this did a
pretty good job, but let's customize this to make it a little bit more realistic

Before

We customize that having one vinyl mix still isn't very useful. So instead inside the
fixtures changes to create many 25. This is where that library shines. If we, our
fixtures now with1 line of code, we now have 25 random fixtures to work with though.
You can see that kind of like the title. Some of this is a little bit, the random
data could be a little bit better. So let's modify that inside a vinyl mix factory
let's change the title instead of being text, which can kind of be a bit of a wall of
text. Let's change it to be words. And then we'll use how about five words and
they'll say true so that it returns this as a string. If you don't otherwise, this
will return. This will the words, method returns in array for track count. We do want
a random number, but we better have a number between about five and 20. That seems
like a good track count for the genre. Let's do random element and pass this pop and
rock. Those are kind of the two genres that we are sup have been working with in our
app. Whoa,

And make sure you call this like a function. There we go. Oh, make sure to have the
function on that. Perfect. And the same thing for votes we can do. We better read
number number between negative 50 and 50, since we can have negative votes much
better. So you can see it added a bunch of our properties here by default, but didn't
actually add all of them. One property that's missing here is description. We'll do
self call on faker, and then there's a method on here called paragraph. And finally
down here for slog, we don't need that at all because that's going to be set
automatically. So now we try it again and refresh super cool. That looks much better.
You can see, we have one broken image here just because that API I'm using has some
missing images on it, but it works just fine. The found Foundry library can do a lot
more cool things. So definitely check out its documentation. It's also super useful
when you write tests and works great with database relations. So you'll see us use it
again in a more complex way in the next tutorial. Next let's add a page, a nation to
this page, cuz eventually we're not going to be able list everything all at once.


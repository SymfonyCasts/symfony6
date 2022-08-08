# Twig

Coming soon...

Our vinyl mix. Entity has a votes integer property, but we're not printing that out
yet on the browse page. No problem. Over in templates, vinyl browse at H two mile
twig. After the created app that add a line break, then print out mix.votes, which
even auto completes for us. And then I'll say votes then lovely. We can see the
votes, which can be positive or negative because eventually will allow our, uh, mixes
to be uploaded or downloaded. All right. So right now we're querying the database and
the results are coming back in whatever order the database wants. How could be query
to show to order these by the highest votes first? Well, we could write a custom
query inside a vinyl mix repository, which we'll learn to do soon, but these
repository classes have several methods to at least let us do some basic things. So
for example, we can of course call find all we can call, find and pass it, the ID to
find a single vinyl mix. But there's also a couple there, like find one by where you
can pass it in array of criteria or find, buy where you can pass it in array of
criteria. So in this case, what we can do is we can actually leverage, find, buy.

This will return an array of results that match whatever criteria we pass here. So we
could in theory, put like name set to some particular name, but in this case, I'm
going to actually leave that empty. So it returns everything. But then take advantage
of this second argument, which is the order by and say, votes, descending, and now
nice highs to votes first. Okay. About these votes, as you can say, they can be
positive or negative to make it even more obvious when they're positive. I want to
print a little plus sign in front of the positive votes. We could do that by adding
some logic into our template directly. But remember we had this nice entity class
right now. It only has getter and center methods in it, but we are allowed to add our
own custom methods to help us fetch the data in nicer ways. So for example, let's
create a new public function, get votes string method, which will return a string
inside of here. We can calculate the prefix like positive or negative with some fancy
logic here that says, if the votes are equal to zero, then we want no prefix else.
We'll call more fan logic here.

If the votes are greater than an equal to zero,

We want a plus symbol else. We want a minus symbol. And let me actually surround this
entire second statement in parenthesis. And if that's a little confusing to you, you
do not need to do that all in one line with two, if statements on top of each other,
you can break that down if you want to, but that should get the job done. So down
here we can return. I'll use sprint F and we'll return percent S that'll be the
prefix and then percent D that'll be the number of votes. And then we'll fill that in
with prefix. And then I'll use the absolute value of this->votes since we're putting
the negative sign in manually.

So it's nice. We now have this nice method that we can reuse anywhere in our project.
Get the vote string inside of our template. We can call this by of course saying, mix
dot, get votes string, if we want to, but we don't really have to even do that. We
can say mix.votes string. Remember TWI smart enough. It will realize that vote string
isn't a real property, but it'll see that there is a GI vote string in it. We'll call
that it's kind of a virtual property inside of twig and awesome. We get the plus and
the negative. And while we're talking about this, these broken images for my
placeholder site, that's currently down are kind of making me mad. So let's refactor
those two. It might be handy to be able to get the image, the URL to a vinyl mixes
image from multiple parts places in our project. So let's create a new public
function here for that called get image URL. And this time let's even allow a little
in, uh, width argument. So you can control the size inside of here. I'm going to use
a different API service with we're able to pass it two different wild cards.

The first is like in ID, they have about a thousand images there. So for that, I'm
going to say this->get ID plus

50%

A thousand. Now that might look confusing, but basically what we're doing is this is
going to be a number between zero and a thousand based on the ID. So that ID one will
always return the same image. ID two will always return the same image. And the plus
50 is just there because the first 50 images are kind of all similar. So I'm skipping
those. And then down here for the next part, it's actually the width that we're going
to pass. So cool. So now we have this nice reusable method. And so back in our
template up here is where I have the hard coded image URL. Right now we can place
this with mix mix.image URL, but this time we do need to pass an argument. So I'll
pass 300 and let's update the alt tag as well for a mix album cover this time, want
to refresh lovely. Our one last tiny cleanup thing before we keep going. We no longer
need this mix repository service, which loads things, the mixes from the database. So
let's delete that. So I don't get confused and try to use it since it has such a
similar name to our new repository class. So I'm going to go ahead and

Go to a refactor delete

To delete it, but we're probably, we might still be using that somewhere, right? So
to figure out where we're using it, we could say, get grip, mixed repository to chap
ware in our code that's being used, and you probably should do that. But one really
cool thing is that Symfony's service container is so smart that oftentimes it will
tell us if we've messed something up. Like if we're still using a service that
doesn't exist, for example, refresh any page and you'll get cannot auto wire service.
Talk to me, command argument, mix repository has this type, but that class is
missing. So even though this page doesn't even use this, talk to me, command class,
it figured out that there's a problem in there. So if you open source command, talk
to me command. Yep. We were using that mixed repository down here, up here so that we
could call a method, find all on it.

Let's change that to use our vinyl mixed repository, and we can remove the use
statement on top the vinyl mix repository diary still has a fine all method. So this
will still work. This is not a very efficient way to find a random mix, but it will
be good enough for now. All right. So I'll close that class and it refreshed again.
It'll get one more spot where we're also using that old mix repository in vinyl
controller. So up in vinyl controller, up in a constructor, we are auto wiring it
there, but we're not even using this property anymore so we can remove it. And I'll
remove its used statement and a couple other used statements that are not being used
anymore more. And now got it. All right. Next, let's learn how to build custom
queries to query. However we want.

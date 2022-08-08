# Request

Coming soon...

I want to allow users to up vote and down vote a mix. So let's add that this will
also be the first time we update data on an entity in our vinyl mix entity. We
created a vote's integer property. So this will need to run an update query up
whenever someone votes open the show template, open templates, mix show H twig. And
to start let's just print mix.vote string votes so that we can see that on there.
Perfect. Now to add an up vote and down vote functionality, we could add, use some
fancy JavaScript, but we're going to keep it simple by adding a button that posts a
form. And remember, in the first tutorial we installed the turbo JavaScript library.
So even though we'll use a normal form, tag turbo will automatically submit it via
Ajax. So we'll get a nice smooth experience. Check this out. Symfony does have a form
component. That's something we'll talk about in the future tutorial. This form is
going to be so simple that we don't really need it anyways. So I'm going to create a
normal, boring form tag set to path, and I'm going to submit this to a new controller
method. So let's go ahead and create that in mix controller.

<affirmative>,

Let's create a new public function called vote

And above this. We'll add our route with the URL. How about /mix /curly brace ID
/vote? And then we know we're going to need to link to this. So I'll give us a name
right now. F_mix_vote. We're going to use the same trick we did before. So we are
going to include the ID of which vinyl mix we're voting on right now. And then we'll
have Symfony automatically query for that by adding a argument type heed with vinyl
mix. And I don't need to, but I'll add the response return type, which I forgot up
here. It's a good practice, but you don't really need it inside of here just to make
sure it's working. We'll DD the mix object. Cool. Copy the name of this route. Go
back on the template. So I'll form action = and we'll generate, uh, a Ural to that
route. And of course this requires an ID wild card, which will be the mix.id. And
then we're also going to have this, the method = post. Anytime you have a form that
is changing data on your server, you want to have method = post. And though I don't
need to do this. If I want to, I can also guarantee that you can only post to this
URL. So I could say methods post

It's. Now, if someone goes to this URL would be a get request. It's not going to
match this route. All right, back over in the form, this isn't going to be a form
that actually has fields in it. We're literally just going to put a button inside of
here. So I'll create a button with type = and then some classes for styling. And then
actually for the text, we'll just use an I a spot awesome icon. So I'll put a span
with class FAA-thumbs up. Perfect. All right. Refresh and awesome. There's our out
vote. And when we click it beautiful, it hits our endpoint. Notice the U URL didn't
change that's because this actually just submitted via Ajax and then our dye
statement hit it. But this did go to /this is hitting our new controller. All right.
And a second, we're going to add another button for thumbs down here. So we're going
to need to figure out which button up or down is being pushed due to that we can
actually add name = direction and then value = up. So now if we click this button, it
will send a post data with direction set to the value up.

But how do we read post data in Symfony? Well, whenever you need to read anything
from the request like post data query parameters, uploaded files, headers you'll need
Symfony's request object, and there are two ways to get it. The first is that you can
auto wire a service called request stack, and then you can get the current request by
saying, request stack = get current request, and then you can call various methods on
it. Then you can access various data on it. This works anywhere that you can auto
wire a service, but in a controller, there's an easier way. So I'm going to undo
that. And instead, I'm going to add argument that is type heed with request, get the
one from Symfony's HT Q foundation, and then we'll call it request. Now that looks
like request is a service and we're auto wiring that service, but request is not a
service we're now seeing. This is a special thing that works only with the request
class.

So we now know four different types of arguments that you can have to your
controller. One, you can have route wild cards like dollars, an ID, two, you can auto
wire services, three, you can type in entities and four, you can type in the request
class. Basically the request object is so important that Symfony created a special
case just for, just for this. And it's kind of beautiful. Our whole job as developers
is to read the request that's coming in and create a response. So how cool is it to
have a, an action method with that has a request argument, input request, and output
response. Anyways, now that we have the request object, there's lots of different
ways to get data off of the request object methods. In this case to get post data,
it's actually request->request and then get, and then the name of the post data we
want, which in this case is direction. The request. We're not going to talk a lot
about the request object. Whenever you need to read something off of it, just read,
find the documentation. It'll tell you what method, uh, how to access that data.

All right, back over here, I'm going to refresh the page up, vote and got it. And
what if the, all right, so let's actually set this to a direction variable direction
= request, okay. Direction. And just in case direction is missing. For some reason,
this shouldn't happen unless somebody's trying to kind of mess with our site, we'll
default it to up. So if there is no direction, post parameter, this will return up as
the default. All right, now let's add the down vote. So I'll copy this entire button
paste. And all we need to do is change direct value to the down and then FA thumbs
down.

All right. So we're either going to get the value being up or the value being down.
So in our controller, we can use this. If direction is up, then we'll say, mix-> set,
votes, mix,-> get votes. Plus one else. We'll do that. Same thing, except the mix
votes minus one, then down here, I'm going to keep, I'm going to DD mix. Once again,
in a real site, you'll probably also store who is voting, which user so that the same
user can't vote over and over again. We'll learn how to do that. And the next
tutorial about doctrine relations, but this will work just fine for now. All right,
so now let's refresh. So we have 49 votes right now up vote 50. And if we refresh and
down vote 48, but we haven't saved this value of the database yet you notice whenever
I refresh, it's still the original 49, let's save this next and also learn how to
redirect, set flash messages and create smart entity methods to make our controller
look a lot cooler than it does now.


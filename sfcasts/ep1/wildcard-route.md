# Wildcard Route

Coming soon...

The homepage will eventually be the place where a user can design and build their
next sweet mix tape. But in addition to creating mix tapes, Users will also be able
to browse other people's mix tape to do that. Let's create a second page with URL
/browse out by adding a second controller function To public function. How about
browse? That name doesn't really matter. And to be responsible, I'll put a response
return type on that. And above this, we need our route. So we're looking exactly the
same, Except that this time we'll make the route /browse Inside. What do we always
return from a controller, a response object to return new, and then I'll just put a
message in there. All right. Let's check it. I'm selling the homepage. So if I
refresh nothing changes there now at head to /browse, and yes, we're crushing it a
second page in under a minute On this page, we'll eventually list, mix tapes from
other users, but to help find something we'd like, I, I want users to also be able to
browse by genre. So like if I go to /browse /death metal,

That would let me see. Only death metal mix tapes. Um, pretty hardcore right now. If
we do this, we get this no route found. This is basically the 4 0 4 page, Which makes
sense. This does not match any of the routes in our system. By the way, what you're
seeing here is Symfony's fancy error, exception page, because we're currently
developing. It gives us lots of details. Whenever something goes wrong. Anyways, the
simplest way to make this URL war is just to change the URL to /browse /Death metal.
That does work, but that's not very flexible, right? We would need one route for
every single genre And which could be hundreds. And we just killed these /browse URL,
which is now a 4 0 4. What we really wanna do is match /browse /anything. And we can
do that with a wild card. So replace the hard coded death metal with open curly,
close curly, and inside SLU. Slug is just a technical word for a URL safe. And we
could have called this anything like genre or cool music category. It makes no
difference, but whatever we put inside of this wild card, we are then allowed to have
an argument with that same slug.

The matching here is done by name. So whatever, if we go to /browse /death metal, it
should pass as that death metal string right here To see if that's working, let's
return a different response, I'll say is your on rock. And then we'll just pass the
slug. All right, let's try that. I'm gonna head back to /browse /death metal And yes,
Try /brows /emo. That works too. Oh, and if you want to, it's optional, you can add a
string type to the Sug argument that does not change anything. It's just a nice way
to program. The slug will always be a will, will always be a string regardless of
this, um, type Later, we'll learn how you could turn number wild card, like the
number five Into an in. If you want to Anyways, let's make this page a bit fancier
instead of just printing out the slog wheel, kind of convert this to a title. So I'll
say title = S St. Replace let's replace any dashes with Spaces. And then down here, I
will use the title in a future tutorial. We're going to query the database

For these genres, but, But right now we'll just kind of manually convert this slog
into a nicer looking title. Can you try this? Eh, it doesn't look any different
formo. If we go to death metal, it at least adds the space between, But to make that
even fancier, check this out, let's add another line, say title = and then you, and
that will auto complete a function literally called you. This is from the, the U
function. We don't use a lot of functions in Symfony. This is a rare case. This comes
from a com string component inside of Symfony. As I mentioned, Symfony is many
different libraries and you're gonna be leveraging those libraries all the time. This
is a library that helps you make string transformations. So what we can do in here,
actually let me move. My S St. Replacement here is we pass it a string as the first
argument, and this returns an object that we can then do operations on. So one of the
methods on that object is call title. You can convert things to title case, and we'll
have that apply to all words. So I'll pass true.

Now we try it. Sweet. It uppercases the letters, the string component isn't
particularly important. I just wanted to show you how you leverage different parts of
Symfony. As you are trying to get your job done, Symfony, just a bunch of tools to
help you do your work. All right, one last challenge Going to /brows /emo or death
metal works, but just going to /browse does not work. It's broken a wild card can
match anything, but by default wild card is required. We have to go to /brows
/something. So how can we make a wild card optional? The answer is delightfully
simple. Make the corresponding argument optional. As soon as we do that, it tells
Symfony's routing layer that the slug does not need to be in the URL. So now when I
refresh It works though, that's not a great message for this page. So let's do a
little bit more work here. We'll say if there's a slug, then we will create the title
the way we were else. We'll set title to genres. Oh, wait. And, and up here in the
first part, let's say genre, colon. There we go. And now down here in the response,
well, just pass the title.

Awesome. Let's try that on /brows, all genres over on email, page genre, emo. Okay.
Next putting text like this into a controller. Isn't very scalable. Especially if we
start, uh, including HTML, we need to be able to render templates to do that. We are
going to install our first third party package and witness the massively important
recipe system, Symfony recipe system in action.


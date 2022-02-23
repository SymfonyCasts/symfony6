# Json Api

Coming soon...

In the future tutorial, we're gonna create a database to manage songs, Genres, and
the mixed vinyl records that our users are creating. Right now, we are working
entirely with hard coded data, but our controllers and especially templates won't
feel much different once we make this dynamic. So here's our new goal. I wanna create
an API endpoint that will return the data for a single song. As JSUN, we're gonna use
this in a few minutes to bring this play button to life come, none of these buttons
do anything, but they do look pretty. The two steps to creating an API endpoint are
exactly the same as creating an HTML page, create a route and a controller. Since
this API endpoint will be returning song data, to keep things, instead of adding
another method Inside of vinyl controller, let's create a totally new controller
class. How you organize this stuff is entirely up to you. So I'll create a new PHP
class called song controller, or you could also call this song API controller. If
you're playing, having a bunch of API endpoint Inside, we're gonna make this look
like your other controller, which means I'll extend abstract controller. Remember
that's that's optional, but it gives us shortcut methods,

No CRI a public, uh, public function called get song And for the route I'll type
through out hit tab to auto complete that. So it adds the U statement and it'll have
the URL B /API /songs /curly brace ID where this will eventually be the database ID
now because we have a, an ID wild card. We are allowed to have an ID Argument. And of
course this stuff is also optional, but we need know that our controllers always
return a response object. So we can auto complete the one from Symfony component HTTP
foundation for now inside. I'm just gonna use our nice DD to print that ID, just to
see if we can get this working. All right. Let's head over to how about /API /songs
/five? And it works All right, in that controller, I'm going to paste in some Song
data. So eventually come from the database. This is what I want to return as JSUN. So
how do we return JSON in Symfony By returning a new JSON response in passing in the
data? That's it Refresh and hello JSON. Now you might be thinking Ryan, you've been
telling us that a controller must all always return a Symfony response object, which
is what render returns.

Now you're returning some other type of response object. Yep. Because JSON response
is a response. Let me explain. Sometimes it's useful to jump into core classes, to
see how they work to do that in peach storm. If you're on a Mac hold command, And if
not, hold control and then click the class. You wanna jump into Surprise JSUN
response extends response. We are still returning a response, but this one
automatically JSON encodes our data. And if we do a little of digging down here,
actually let me search. There we go. It also sets the content type header down here
to applications /JSUN. This class also has a couple of other superpowers. If you
wanna dig into it, The point is we are still returning a Response. It's just that
this subclass makes returning a JSON response easier. Oh, and we can be even Laz than
this By saying, return this->JSON song. Whereas that JSON method come from that's the
second shortcut method that we've learned from abstract controller, we'll learn more
and more shortcut methods along the way And doing this makes absolutely no difference
because this JS shortcut method is just a shortcut to return a JSON response. Oh, by
the way, Symfony has a serializer component that is really good at turning objects
into JSON. And then JSON back into objects. It's a central tool. If you're building a
powerful API, we talk a lot about it in our API platform tutorial, which is a
powerful tool for creating APIs that leverages the serializer

Anyways at your terminal. Now that we have our new page, let's run debug router
again. Yep. There's our new endpoint Notice that this table has a column called
method that says any, That means you are allowed to make any a request with any HTTP
method like GI or post, and it will match that route. But the purpose of our new API
endpoint is to allow users to make a GI request to fetch its data. But technically
right now you could also make a post for request to this and it would work just fine.
We might not care about this, but often with APIs, you'll want to restrict an
endpoint to only work with one method like GI or post can we make this route somehow
only match get requests? Yep. By adding another option to our route In this case,
it's called methods. You can see it. Auto completes. This takes an array and inside
we'll say, get, and again, as a reminder, I'm gonna hold command and click into that.
All of these options are And described in the, a instructor of that class Back over
on debug router. Nice. Now it says that this method only accepts get requests, but it
is kind of hard to test this. Since in a browser, we can only make, get requests.
This is where another bin consult command comes in handy.

It's called bin console, router coin match. And if we just run it with no arguments,
it gives us an error, but it kind of shows how it's used. So you can say router match
/API /song /about 11. And it will tell you that that matches are This route. Or we
can try it with our dash method. So let's say dash method = post. And it says, no
routes match this, this path with that method. But it does sound that it almost
matches this route. It's just the at post does not match the allowed methods get All
right, let's do one more thing to tighten up our new endpoint. I'm gonna add an in
type end before ID that doesn't change anything really, but PHP will now take the ID
from the URL And cast it into an int, which is just nice. Cuz then we're dealing with
a true integer inside our coat. You can see this subtle difference here. Notice right
now that when we return our JSON response ID is a string and I refresh ID is now a
true integer in JSON. But one, if somebody was tricky and wanted to /APS salon last
long /apple

Yikes,

Huge air. And this is a 500 air that's no Boyo we do not want that. But what can we
do? the error comes from Air, comes from when Symfony is trying to call our, our
controller. So it's not like we can Put code down here to check if it's a number it's
too late, But Hmm. What if instead we could tell Symfony that this route should only
match if ID is a number, is that possible? Yep. By default, when you have a wild
card, it matches anything, but you can change to match whatever regular expression
you want. How inside of the curly braces, right after the name do an open less than
greater than then inside of here, The inside of a regular expression. So I'm gonna
say /D plus What this means is Match a digit of any length. Now, if we refresh yes, a
4 0 4, no route found it simply did not match this route. But if we had back to /API
/song /five, it works Next. If

I had to choose one topic that is the most central and super important thing in
Symfony,

It would be services. Let's find out what a service is next.


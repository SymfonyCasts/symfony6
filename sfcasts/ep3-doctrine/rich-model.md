# Rich Model

Coming soon...

After you submit a form successfully. We always redirect. And often we want to
additionally, show the user a success message. After that redirect Symfony is a
special way to handle this flash messages. So before we redirect, we can use a
shortcut method on the base controller called add flash for the first argument, pass
this success. And then for the second argument, the Mo message you want to show to
the user vote, count it. The success key could be anything. It's kind of like a
category for the flash message. And you'll see how we use that in a minute. Now,
flash messages have a fancy name, but they're a simple idea. This stores the message
in the user's session. What makes flash messages special is that it will be
automatically be removed after we read this message one time, they're kind of like
self destructing messages. How do we read it?

Well, the way I like to do it is actually to open up my base template based at H two
mile twig and read and render them here. So let's put it right after the navigation,
before the block body, we'll say four message in, and then to read the, what we want
to do is read out any success category, flash messages. As we have to this, we can
leverage the one global variable that we have in Symfony, which is called app. This
has several methods on it. Like the environment you can get the request, the session,
the current user, or one called app.flashes. This is a method where we pass it, the
category. So success. So as I mentioned, this could be anything you could put
dinosaur here. If you put dinosaur here in a controller, you'd read the dinosaur
messages out there and then we'll put N four.

Now, typically you only have one success message in your flash at a time, but
technically you can have multiple. That's why we're looping over them. Inside of
here, let's render a div with class alert, alert success. So it looks like happy
message. Then it's outta that. Well, you will print out message. So if this works
correctly, it will read all of our success flash messages and just by reading them
and then render them. But now because they're red, they'll be removed and won't
render again by putting this in the base template, we can now set flash messages is
from anywhere in our system and they will be rendered right on the page. All right.
So check it out up, vote and beautiful. Probably want to really move this extra
margin here in a real project, but we'll leave that. All right. Look back at mix
controller. The logic for doing our up and down voting is pretty simple, but I think
it could be more clear. Try this open up vinyl mix and scroll down. Actually let's
find get votes right after set votes, just to keep things organized, create a new
function called up vote.

This will return nothing return void inside. Just say this->votes plus plus, and then
copy that and create a second method. You guessed it called down vote, which is votes
minus minus. Now, thanks to these methods in our mix controller. Instead of saying,
having mixed set votes equal, instead you mix get votes. Plus one, we can say
mix->up, vote and mix->down vote. Now that is nice. Our controller reads much more
clear clearly, and we've encapsulated the up vote and down vote logic into the
entity. And over here, it even still works. Now, this highlight's an important topic.
We have added three custom methods to our entity. One that helps read the data in a
special way. And two that help set the data. When we run `make:entity`, it creates
getter and setter methods for every single property. That's nice because it makes our
entity infinitely flexible. Anyone from anywhere can read any property or set any
property, but sometimes you might not want or need that. For example, do we really
want a set votes method? Is there really a use case in our code for something to set
the vote, count to any number that it wants? Probably not. Probably all we need are
up vote and down vote though. I am going to keep this set votes method because we use
it when we generate our dummy, uh, vinyl mix.

But this touches on a really interesting idea by removing unnecessary getter and
setter methods in your entity and replacing them with more descriptive methods like
up, vote down, vote, get vote string, or get image URL that fit your business logic.
You can little by little, give your entities more clarity of vote and down vote are
super clear and descriptive someone calling these doesn't even to even need to know
or care how they work. Internally. Entities that only have Gitter and insider methods
are sometimes called anemic models. Entities that remove these and replace them with
specific methods for your business. Logic are sometimes called smart models. Now some
people take this to an extreme and have almost no getter setter methods here at
Symfony casts. We tend to be more pragmatic. We usually do have getter setter
methods, but we always look for ways to make them more descriptive for ways to be
more descriptive, like a vote and down vote. All right, next, let's install an
awesome library called doctrine extensions. This is like a magic library full of
superpowers, like automatic timetable in slug creation behaviors.


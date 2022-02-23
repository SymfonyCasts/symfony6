# Route Controller

Coming soon...

I gotta say I missed the eighties. Well, not the outfits and definitely not the hair,
but the mix tapes. Oh yeah. Michael Jackson, Phil Collins. I have the coolest mix To
capitalize off of that nostalgia, but with an aim at hipsters and audio files, we are
going to create a new app called mixed vinyl, a store where users can create mixed
tapes complete with boys, to men, Mariah Carey, and queen back to back except printed
onto vinyl or a record. The only thing I'm gonna need now as a record player in my
car, The page we're looking at, which is super cute and changes colors. When we
refresh is not a real page. It's just a way for Symfony to say hi and link us down to
the documentation. And by the way, the Sy documentation is great. So definitely check
it out as well as you're going through things. All right? Every web framework In any
language has the same job to help us create pages. Whether a, those are HTML pages or
J API JSON responses. And pretty much every framework does this in the same way, via
a route controller system. The route defines the URL to a page and points to a
controller.

The controller is a PHP function that then builds that page. So route plus controller
= page it's math. We are going to build these two things kind of in reverse. So first
let's build the controller function in Symfony. The controller function is always a
method inside of a class. I'll show you in the source controller directory create a
new PHP class. Let's call it vinyl controller, Congrats our first PHP class, and
guess where it lives in the source directory, because all PHP classes will live in
source. And for the most part, it doesn't matter how you organize things inside of
source. You can put things into whatever directory you want and call them whatever
you want. So flex your creativity. Oh, and notice the namespace that PHP storm put at
the top of the class automatically. It is app /controller. This is important.
However, you decide to organize the, in your source directory, your namespace must
match your directory structure starting with app. So you always have, I kind of think
of the app. Namespace here is kind of like pointing to your source directory. And
then if you have something in a controller, sub directory, it needs to have a
controller namespace. If we create a directory here called pizza and put a PHP class
in it, namespace would be app /pizza.

If you ever mess this up, like do a typo on this or leave it off, you're gonna have a
bad time. Symfony's not PHP is not going to be able to find the class that you have
inside this file. So you'll get errors like class, not found vinyl controller. So pay
close attention to make sure that your namespace follows your, your directory
structure, starting with app. Anyways, back to our job of creating a controller
function Inside of here, let's add a new public method Called homepage. And the name
of this method does not matter at all. You could call that anything. And for now, I'm
just going to put a dye statement with a message. All right, that's a good start. So
step two, create a route that defines the URL to our new page and then points to this
controller. There are a few ways to create routes in Symfony, but pretty much
everyone uses the same way. It's called an attribute. Here's how it works Right above
this function do on sign left, score racket, right square Brackett. This is PHP eight
attribute syntax, which is kind of like a configuration syntax inside up here. Start
typing route. Now, before you finish that, you notice it's auto completing something
for me, hit tab,

Cuz that's going to finish route for me. But more importantly, it's going to add a U
statement on top. Whenever you use an attribute, you need to have a U statement for
it on top, anyways, pass this /which will be the URL to our page. So we are creating
the homepage And that's it. This route defines the URL and points to this controller
just because it's right above that controller. So let's try it. Refresh the homepage
to see Congratulations looked at the URL, Saw that it matched our new route /an empty
quote or kind of the same thing in this case, And then called our controller
function. Oh, and by the way, I keep saying controller function. The controller
function is sometimes called the action just to confuse things more. All right. So
inside of the controller Or action, you can write whatever code you want to build.
The page, make database queries, make API calls, render a template, whatever we are
going to do. All of that. Eventually the only thing that Symfony cares about is this
that your controller returns a response object. So check this out, Let's return And
then start typing response and notice there's gonna be a few of these response
classes. Two of them, even from Symfony, the one we want is the one from HTTP
foundation.

The HTTP foundation component or library is the part of Symfony that gives you things
like the re a request response session and other S So I'll hit tab to auto, complete
and finish that. Oh, I should have said return new response. There we go. Now we'll
hit tab. Now, when I hit tab the first time, very importantly, it added this U
statement up here. Every time we reference a class, we need a U statement on top. And
by adding PHP storm auto complete the class for me, it adds the use statement
automatically, by the way, if you're still kind of new to PHP namespaces and use
statements, check out our short PHP namespaces, tutorial Inside here. I'm just going
to put a message Like the title of the Mixed vinyl we're working right now is called
PB and jams. All right, let's see what happens. Refresh and PB and jams. It doesn't
look like much yet, but team that's our first fully functional Symfony page
controller, page profit. You've already learned the most foundational part of Symfony
and we're just getting started. Oh. And because our controllers always return a
response object, it's optional, but you can add a return type to this function that
doesn't change anything. It's just a nicer way to code. And if we ever return
something, that's not a response, we'd get a really clear error from PHP. All right.
Next I'm feeling pretty confident. So let's create another page, but with a much
fancier route that contains a wild card. Okay.


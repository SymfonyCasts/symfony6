# Twig

Coming soon...

I'd like to think of Symfony as two parts and surprise. You've already mastered. The
first, the first half of Symfony is the route controller response system, which we've
seen you create a route, create a controller, return a response, and have a sandwich.
The entire second half of Symfony is all about learning and mastering a set of
optional tools that Symfony provides that will make your job easier inside of your
controller. For example, if you need to make a database query from inside a
controller, you can totally connect the database Manually And do the query manually
all by yourself. Symfony doesn't care. You won't hurt its feelings, but Symfony does
provide a tool for doing that, which you can choose to use. If you want to. Right
now, I want to render a template so that we can write HTL code in an organized way.
And while we could create some system to do that manually, we are going to rely on a
tool named twig, Which is one of my favorite parts of Symfony. Now, obviously Symfony
controller classes do not need to extend some base class. As long as your controller
function returns a response object, Symfony doesn't care what your controller looks
like at all, but you usually will extend a base class called abstract controller.

Why very simply because it gives us shortcut methods.

And the first one is the render shortcut method for rendering a controller. So return
this->render, and you're gonna pass those two things. The first is the name of the
template. So I'm gonna call this vinyl /homepage dot H team on that twig. You don't
have to, but it's very common to have a directory with the same know as your
controller and then a template name. That's the same name as your method, but you can
do whatever you want. The second argument is an array of any variables that you want
to pass into your template. So I'm going to create a variable called title, and we'll
set that to our mix tape title, PB and jams. And that's it. Oh, but pop quiz. What do
you think render returns? Well, hopefully at this point You are already in your head.
That thing I keep saying that your controller must always return a response object
and yes, render is just a shortcut to render a template, get that string and put it
into a response object for you. Render returns a response. Okay. We know from earlier
that when you render a template with twig, it looks in the templates directory for
it. So let's create a new sub directory in there called vinyl

And inside of that, a new file called homepage dot .html.twig. And before we talk
about twig too much, I'm gonna put a little template in here. I'm gonna put an one
and then use a special syntax, curly, curly. And then I'm going to use that title
variable that we just passed in and down here. Uh, let's put some hard coded todo
text. All right, let's see if this works head over. We were working on our homepage.
So let's go to homepage and Hello twig. So twig is one of the coolest parts of
Symfony and also one of the easiest. So we're gonna go through every thing you need
to know about SWG in basically the next five minutes in twig. There are exactly three
different syntaxes. If you need to print something, you use curly curly. I call this
the, say something syntax. So curly curly, that basically means open PHP echo. So if
I said cur early curly, say something that would be printing a variable called, say
something. Once you're inside twig, it's a lot like JavaScript. So curly, curly say
something would print say something. If I surrounded that in quotes, that would
literally print the string, say something. And there are also function of twig, which
we'll talk about in a second. So that would call this function and then print the
result.

So the first syntax is curly curly, and that is the, say something syntax. The second
syntax, isn't really a syntax at all. It's just The comments decks, curler erase
pound on. And then a comment that won't show up on the page that won't execute. It's
just a comment. And then the third and final syntax and twig is called. I call the,
do something syntax. This is when you need to do, you're not printing your doing
something in the language or, or doing some sort of language construct. Good examples
would be if statements or four loops. So let's actually try a for loop. So I'm gonna
go back into our controller and I'm going to paste in a tracks list, some awesome
songs. And then down here to get the, make that variable available in my template,
I'll pass a variable called tracks set to the at array. Cool. Now, unlike title since
tracks is an array, we can't just Print tracks like that or not gonna have a good
time. It's array to string conversion. Instead we want to loop over it.

So let's say tracks and I'll put a little UL here and then to loop, we're gonna use
that, do something syntax, which is curly brace, pound sign, curly brace percent. And
then the thing that you wanna do like for loop or an if statement or setting a
variable, and I'll show you where you can see all of the, do something tags in a
second for a for loop, looks like this. You say four track in tracks. So tracks is
the variable we're looping over and inside the for loop. This is gonna be the
variable for each one in that you'll do N four N four at the bottom It's out of here.
Very simply. We'll do ally and we can use our say something tag to print track. Now.
Got it. And you know what, let's actually make this a little trickier before we talk
about more, more about twig. I'm gonna go back into my, uh, controller and I'm going
to, instead of this being, I'm gonna kind of like restructure this array and make it
a little more complicated. So I'm gonna say song equals. And then for the Artist,
I'll say artist

Like that, gonna type that all manually. I just wanted you to see what it looked
like. Oh, should paste that. Oh, there we go. So no surprise. When we try now, we are
going to get an error, a array to string conversion, because whenever it loops over
these tracks, each track inside of it itself is now an array. So track is an array.
So I mentioned that twigs a lot like JavaScript. That's why I wanted to show you this
in JavaScript. If you had something that had Song and artist keys, you would say just
Track.song And track dot artist and in twig. That's also exactly how it works. That
gets our list working again. All right. So I want you to head to twig.Symfony.com to
check out and then click to check its documentation. There's lots of good
documentation on here, but what I want you to do is scroll down to the twig
reference. First thing you see over on the left here is this thing called tags. This
list here is 100% of everything that you can use with the, do something tag. So you
can see four here. And if here, so if you start, if you're using curl race percent,
this next word here is always going to be one of these things from this list. And
honestly, you're only gonna use about five of these, um, on an everyday basis.

And if you ever need to know how to use something, you're just gonna check its
documentation. And it's gonna show you how in if statement looks In addition to 20
tags, twig also has something called filters. These are sweet filters are basically
functions with a more hipster. Syntax. TWI also has functions, but you see there are
fewer than them. Twig really prefer filters. Cause they just look cooler. For
example, there is a filter called upper the way the filters work. It's like using a,
um, command line. You have a string and then you use pipe and then use the filter
name, cause this would take this string and send it into the upper filter. So we can
actually see this. We'll say track to artist, but then we'll say upper. And when we
try that, we get upper. If you wanna start really messing around things, you're gonna
then pipe that into lower and really confuse your coworkers as to why you're doing
that. And it will go down to lower. So filters, there are many filters in this list.
You should check them out so you can remember what's there. They are one of the
coolest parts of TWI. And as mentioned, there are also functions. And then there are
a couple other fancy things called tests, which are handy in if statements and
operators, but for the most part you've seen it all.

All right, for the last part of twig, I want you to act view the page source of our
page. Notice that there is no HTL structure. There's no HTL tag or head tag or body
tag. Literally the HTML we have inside of here is the HTML from our, our template. So
is there some sort of layout system in twig where we can add a base layout around us?
Absolutely. And it's incredible. It's called template inheritance. So if you have a
template where that, and you wanted to use a layout, the very top of template, you
were use the do something tag called extends. And then you're gonna say base dot H
channel twig that is actually pointing at this template right here. Now, before we
look at that template, If we had to over and refresh, we are gonna see a giant air.
It says a template that extends another one cannot include content outside twig
blocks To figure out what that means. Open up base dot H team on that twig. This is
your base layout file for twig. And you're free to customize this. How however you
want. This is your template. What I want you to notice here is that

It's mostly just boring HTML, except it has a number of blocks in it. Blocks are
basically holes into which a child template can place content. Let me explain that a
different way. When we say extends based do HT twig up here, that basically says, Hey
twig. When, when I, when you render me, when you render this template, I want you to
use base HG twig as my base lab. The reason twig gives us this giant air over here is
that it says, okay, cool. I can do that. But where do you want me to put all of this
content? Do you want me to put it at the bottom of the pay page at the top of the
page, somewhere in the middle of the page, twig doesn't know, we have to tell it the
way we do that Is by overriding a block. So the reason that this based that H similar
twig template comes the block called body, is that you're probably gonna wanna be
able to put content right here. So to do that in our template, we can surround all of
our content with block, body, And block. We are overriding that block with this new
content.

So now when it renders based that HT twig, And it gets to this block body part, it's
going to use our block body content and print it out right here. Watch let's refresh
air is gone. And if you view the page source, we have a full HTML page. By the way,
the names of these blocks are not important. If you wanted to change this to block
content, go for it, just make sure that you also update it to be called block content
in your child template. Uh, and if you wanted to add more blocks, do it. You can add
as many blocks as you want in here. Every block you added this template is just
another override point. And you'll also notice that blocks can have default content.
So if you look on a page right now, you can see that the title says welcome. That's
because this block has default content and we are not overriding it in our template.
So let's change this welcome to Mixed vinyl. So now by default, that will be the
title of every page in our site. But if we wanna override it, we can, we just need to
override the block called title.

So either above block body or the order, these blocks doesn't matter, I'll say block
title, End block. And then in between, I'll say, create a new record. Thanks to this.
I'm gonna refresh. Now this page gets the custom title.

Oh. And if you're ever wondering, oh, when you might be wondering what if I don't
want to replace a block entirely, but I want to add to the block. You can totally do
that. So in this case, in base HW, we have kind of our, the name of our site and
mixed vinyl up here. Let's say that we wanna just, prepend the custom title. So what
we can do is we can say, create a new record. That'll put a nice little pipe icon,
and then curly curly, use the, say something tag and call a function called parent
that does exactly what you'd expect. It goes and grabs the parent templates, content
for blah title, and then print it right here. We try it. That works. If you're ever
confused about how template inheritance works, it's helpful to think about it. Like
object oriented, inheritance,

Where home page, our homepage template is like a class that extends based at each of
tweaks class. And each of these blocks are like a method. So if we don't override a
block, it's, it will use the default content. But if we do override a block, it will
use our block. What we can also call the parent block if we want to. All right. So
that's it for twig. You are basically a twig expert. You have your P D inside of
twig. So let's move on. Next. One of the killer of Symfony are its debugging tools.
Let's get these installed and check 'em out.


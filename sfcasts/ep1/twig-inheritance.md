# Twig Inheritance

Coming soon...



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


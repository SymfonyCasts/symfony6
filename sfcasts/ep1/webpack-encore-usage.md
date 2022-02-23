# Webpack Encore Usage

Coming soon...

When we install the Webpack Encore, its recipe gave us this new assets directory,
check out this app dot JS file. Interesting. Notice how it imports this bootstrap
file. That's actually bootstrap dot JS. That's this file right here. The, the dot JS
is optional. So one of the key things with this new web back with the Webpac Oncor
system is you can actually have one JavaScript file import, another jib JavaScript
file, and even load functions or variables from it. We're gonna talk more about this
boots up dot JS file a little bit. What I really wanna a look at now is that this
also imports a CSS file. If you haven't seen this before, it might look a little
weird JavaScript importing CSS. Right now that CSS file just has background light
grit To see how this all works. I wanna put some changes in here that we can notice
In APPT JS. I'm gonna say console.log( And in app dot CSS, it already has a body
background. I'm gonna add important on there so that it definitely shows up up. All
right. So who reads these files? Or how are these two files used to answer that

Open up Webpac dot config dot JS. When you run Webpac is a, is an executable file
that we're going to run in a few minutes when you run Webpac it knows to load this
Webpac pack config. I JS what we do inside of here is build a big config tree. And
then we return that configuration at the bottom. This tells Webpac all of the things
that we want it to do. And while there are a lot of features inside of Webpac, the
only thing we need to focus on right now is this one here, add entry. This word app
right here could be anything like dinosaur. It doesn't matter. I'll show you how,
what, what that's, how that's used in a second. The important thing is that points at
the assets /app dot JS file Because of this app dot JS is the first and only file
that Webpac will parse. What Webpac does is it reads this app dot JS file and then
follows all of the imports recursively until it finally has a giant collection of job
of final job, a script in CSS To run web back and see this in action, find terminal
and run yarn

Watch. You can actually see below it says Encore dev dash watch. If you look in your
package that JSON file. One of the other things, the original recipe gave us was a
script section with a bunch of shortcuts. So you'll see me. You'll see me think, say
things like yarn watch, or you can say NPM run watch, and that's a shortcut for
running yarn Encore dev dash watch. So we're executing Encore with this nice yarn
watch command. And this does two things After this command. So what did this command
do? It actually created a new public build directory and inside of that, most
importantly, in app dot CSS and an app dot JS file. So what Webpac Encore does is it
reads this app dot JS file follows. All of the imports gets that final collection of
CSS and JavaScript and writes it to app dot CSS and app dot JS. And the reason these
files are called app CSS and app do JS is because of this entry name here, but this
app dot JS file, doesn't just contain the code in this file. It contains rapid in
some weird web stuff that you don't need to worry about. This actually contains all
of the code. Um, no, not gonna say that,

That we import from here. Okay. So the takeaway is that thanks to Encore suddenly
have new files in a public bill directory, which contain all the JavaScript and CSS
from here. And if you move over to your homepage and refresh, it works instantly, you
saw the background change. And if I open my inspector, there's my console log. How
the heck did that happen To answer that open your base layout template /base dot H
twig. One of the things we have in here that we haven't really talked about Are, is
this Encore entry link tags app in Encore entry, script tags app. These are awesome.
What this says is This first line basically says to go in at a link tag for the
public build app dot CSS file. The second one is a script tag for the public app app
dot JS file. You can see this, if you view source on the page Down here. Yep. They're
link tag for build /app CSS and then a script tag for /build /app JS. Now you may
notice that there's some other weird giant job script file here. That's because
Webpac is really smart for performance purposes

Instead of dumping one gigantic app dot JS file. Some Webpac will split it into two
smaller files. That way the users can download them more quickly. Unfortunately, this
Encore entry script tags function is smart. It will include all of these script tags
needed to get the app entry working. So it's almost an implementation detail that
it's split into multiple files. The takeaway here is that the code that you have in
your app entry, including things that we import is now functioning and showing up on
our page. And thanks to running yarn, watch Encore is still running the background,
watching for changes to those files. So check this out, let's go into app dot CSS.
I'm gonna change this to maroon, hit, save, move over Refresh. And it instantly
changes that's because Encore noticed that change and recompile that file really
quickly. So this means that we have a little system inside of the assets directory
for writing JavaScript and CSS. So let's move our public files into this. So I'm
gonna open up public styles, app CSS, copy all of this, Delete that directory, And
then paste it into here. Now, thanks to this and base that each Timma twig, we know
that Encore entry link tags is gonna load everything from app dot CSS. So we don't
need this style sheet up here anymore

And refresh looks great, But in base that H on twig, what about these external link
tags for bootstrap font? Awesome. And a font? Well, you can to, you can totally just
keep those there if you want to, but we can also process these through our ENCO
system, how by installing bootstrap and font awesome as vendor libraries, and then
importing them, check it out. I'm going to remove all of these link tags right here,
and then refresh that. Surprisingly, it looks terrible. All right. So first thing I'm
gonna re add is bootstrap. So find your terminal. And since this watch is running,
I'm gonna open a new tab and run yarn ad that's the same as composed require
bootstrap dash dev the dash dev here is kind of a best practice, but is not really
important at all. What this does, this does three things. First, it added bootstrap
to our package dot JS file. Second it down, the, a bootstrap into our node modules
directory. You would find it down there. And third it updated the yarn dot lock file
to, with the exact version of bootstrap that we just downloaded. Now, if we stopped
here that doesn't make any difference. We just downloaded bootstrap, but we are not
using it

To use the bootstrap CSS. We can go into our app CSS And just like we, we can do
inside of Java file. We can import other CSS files using the at import syntax. So at
import, And from here, you can normally just, you could, you could reference other
files Like that, that /other file that CSS, or if you want to, uh, import a vendor
library from node modules, you can use little trick till day, and then the name of
that library. That's it. Now, as soon as we did that, Encore rebuilt our app dot CSS
file, and it now includes bootstrap showing more refresh we're back. All right. The
only two other things we're missing are font awesome. And an icon To add those, I'm
gonna run yarn a and then already done some research into the names of the libraries
we need. So the first one for the font is font source /Roboto, condensed dash dev,
And then RN add At Fort. Awesome. So font awesome dash free. So again, that downloads
those two libraries into our project, but it doesn't automatically use them because
those are both CSS files. We're gonna go back into our app CSS and just import them.

So at import, Till they At Fort awesome /font, awesome free, and then import till day
At import till day at font source /Roboto condensed. And we're looking for here is
there should fix this icon up here and you should see the font change, watch the font
here, and it does the font change, but ah, the icons are still kind of broken. I'm
actually not totally sure why that doesn't work out of the box, but the fix is kind
of interesting.

I'm gonna hold command, hold commander control. And actually you can a click this
font awesome free when you do this syntax here. What that actually does is go into
your node modules, director you're at Fort awesome director, your font, awesome free
directory. And then if you don't put any file name after this, there's a mechanism in
here where this library can tell us which CSS file should be input. And by default it
imports this font awesome dot CSS file. For some reason that doesn't work. What we
want is this all dot CSS. So we can actually include that by just following the path
/CSS, you can say, I get auto completion /all dot CSS, And now we're back PH. So the
reason that this system is so awesome is primarily because it allows us to do proper
imports. We can organize our JavaScript into what are called modules that, uh, have
classes and functions and then import the them to share code no more needing global
variables. Wepec en also allows us to use, um, serious stuff like react or view. You
can even see spots in here to enact enable, react, support, or view support, or any
front-end framework you want. But usually I'm like using a delightful, uh, JavaScript
library called stimulus. And I wanna tell you about it next.


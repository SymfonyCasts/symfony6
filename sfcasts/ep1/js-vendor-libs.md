# Installing 3rd Party Code into our JS/CSS

Coming soon...

So this means that we
have a little system inside of the assets directory for writing JavaScript and CSS.
So let's move our public files into this. So I'm going to open up public styles,
app CSS, copy all of this, Delete that directory, And then paste it into here. Now,
thanks to this and base that each Timma twig, we know that Encore entry link tAJAX
is going to load everything from app.CSS. So we don't need this style sheet up here
anymore

And refresh looks great, But in base that H on twig, what about these external link
tAJAX for bootstrap font? Awesome. And a font? Well, you can to, you can totally just
keep those there if you want to, but we can also process these through our ENCO
system, how by installing bootstrap and font awesome as vendor libraries, and then
importing them, check it out. I'm going to remove all of these link tAJAX right here,
and then refresh that. Surprisingly, it looks terrible. All right. So first thing I'm
going to re add is bootstrap. So find your terminal. And since this watch is running,
I'm going to open a new tab and run yarn ad that's the same as composed require
bootstrap-dev the-dev here is kind of a best practice, but is not really
important at all. What this does, this does three things. First, it added bootstrap
to our package.JS file. Second it down, the, a bootstrap into our node modules
directory. You would find it down there. And third it updated the yarn.lock file
to, with the exact version of bootstrap that we just downloaded. Now, if we stopped
here that doesn't make any difference. We just downloaded bootstrap, but we are not
using it

To use the bootstrap CSS. We can go into our app CSS And just like we, we can do
inside of Java file. We can import other CSS files using the at import syntax. So at
import, And from here, you can normally just, you could, you could reference other
files Like that, that /other file that CSS, or if you want to, uh, import a vendor
library from node modules, you can use little trick till day, and then the name of
that library. That's it. Now, as soon as we did that, Encore rebuilt our app.CSS
file, and it now includes bootstrap showing more refresh we're back. All right. The
only two other things we're missing are font awesome. And an icon To add those, I'm
going to run yarn a and then already done some research into the names of the
libraries
we need. So the first one for the font is font source /Roboto, condensed-dev,
And then RN add At Fort. Awesome. So font awesome-free. So again, that downloads
those two libraries into our project, but it doesn't automatically use them because
those are both CSS files. We're going to go back into our app CSS and just import
them.

So at import, Till they At Fort awesome /font, awesome free, and then import till day
At import till day at font source /Roboto condensed. And we're looking for here is
there should fix this icon up here and you should see the font change, watch the font
here, and it does the font change, but ah, the icons are still kind of broken. I'm
actually not totally sure why that doesn't work out of the box, but the fix is kind
of interesting.

I'm going to hold command, hold commander control. And actually you can a click this
font awesome free when you do this syntax here. What that actually does is go into
your node modules, director you're at Fort awesome director, your font, awesome free
directory. And then if you don't put any file name after this, there's a mechanism in
here where this library can tell us which CSS file should be input. And by default it
imports this font awesome.CSS file. For some reason that doesn't work. What we
want is this all.CSS. So we can actually include that by just following the path
/CSS, you can say, I get auto completion /all.CSS, And now we're back PH. So the
reason that this system is so awesome is primarily because it allows us to do proper
imports. We can organize our JavaScript into what are called modules that, uh, have
classes and functions and then import the them to share code no more needing global
variables. Wepec en also allows us to use, um, serious stuff like react or view. You
can even see spots in here to enact enable, react, support, or view support, or any
front-end framework you want. But usually I'm like using a delightful, uh, JavaScript
library called stimulus. And I want to tell you about it next.

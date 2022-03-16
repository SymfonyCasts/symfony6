# Turbo

Coming soon...

Welcome to the final chapter of our intro to symfony6 tutorial. If you're watching
this, you're crushing it. And it's time to celebrate by installing one more package
from Symfony. But before we do, as you know, I'd like to commit everything both to
celebrate our changes, but also so that we have a clean set of files. Just in case
the new package installs an interesting recipe. All right. So the library we're going
to install is called composer require symfony/UX-turbo. See that UX thing right
there. Symfony UX is a set of libraries that add JavaScript functionality to your
app. Often with some PHP code that makes it even easier to work with that.
JavaScript. For example, there's a library for Ren UX library for rendering charts
and another for using an image Cropper library with the form system. So as you can
see, this did install a recipe, so I'll run and git status so we can check out what
that did. Okay. Most of this is pretty easy config/bundles.php means it enable the
new bundle. The two interesting changes are asset/controllers.json And package.json.
Let's check out package.json first.

When you install a UX package, what that usually means is that you are integrating
with some third party JavaScript library. So what that package does is it adds that
library to your code. So in this case, the JavaScript library we acting with is
called at Hotwired/turbo. So added it here for us. Also, the symfony UX turbo PHP
package itself comes with some extra JavaScript. So it adds this special line here.
What this says is add a node package called at symfony/UX/turbo, but instead of
downloading it, you can just find the code for this at vendor symfony, UX, turbo
resources, assets. So you can literally look down here at vendor symfony, UX, turbo
resources, assets, and here is a mini JavaScript package right there Now because it
just updated our yarn, our package,.json file find your terminal. That's running
yarn, and you can see it has some errors here. We need to restart it. You, it has the
file, uh, symfony UX package.json can not be fine. Try running yarn install --force.
So I'm going to hit control C to stop that and then run yarn install --force or node
install -F that will re download download the new packages and make sure that node is
aware of them. Then you can run yarn. Watch

The other file that the recipe modified was asset/controllers.json. Let's go take a
look at that asset/controllers.json. This is another thing that's unique to symfony
UX. Normally, if we want to build a stimulus controller, we put it in the
controller's directory, but sometimes you might install a PHP package and that PHP
package may want to add its own stimulus controller into your application to help out
with something that's actually what this syntax does right here without going into
too much detail. This basically says, Hey, go load a stimulus controller From that
new @symfony UX turbo package, we just loaded. So this adds a new stimulus controller
to our system. Now this stimulus controller is a little bit of a weird one. It's not
one that we are actually going to use directly, you know, by Using stimulus
controller. And then like the name of that controller or anything like that. This is
kind of a fake controller. And just by it being imported, it's going to activate that
turbo library behind the scenes. Okay. So I keep talking about turbo. What is turb?
So the end result of all of this is that just I running that compose required command
and then reinstalling yarn turbo, the JavaScript turbo library is now active and
running on our site. So what the heck does turbo do? It's very simple. It
automatically turns every single link click on our site into an ajax call, which
makes our site appear to be lightning fast. Check it out. I'll do one last refresh.

And then watch if I click browse, there was no full page refresh. If I click these
icons here, no full page refresh turbo intercepts that and makes an ajax call. Let me
make this a little bit bigger. Here

Makes an ajax call to those endpoints and then puts that HTL onto our site. This is
huge because for free, our application is all suddenly looks and feels like a single
page application without us doing anything. Now, one other cool thing you're going to
notice is that even though it's making these ajax calls every time you make an ajax
call, the ajax call actually shows up on the web debug toolbar, and you can click to
go see the profiler for that ajax call really easily. This is actually even more
useful when you think about the API endpoint. So if I hit this play icon here, see
that seven just went up to eight and here's the profiler for that API require. So I'm
going to hold, I'm going to open this link in a new window. And yeah, this is a super
easy way to get to the profiler for that ajax request. So turbo was amazing. It can
do a lot more. There are some things you need to know about it, and if you're
interested, You should watch our tutorial on it. Um, it is something that's turbo is
something that, where it works best. If you add it to your application from the
beginning, which is why I took some time to show it to you right now, but you can
also retrofit and retrofit an existing site to use it. All right, congratulations.
The first symfony6 tutorial is done. Join us for the next, in this series, which will
take you from a symfony from a budding symfony developer,

To someone who really understands what's going on. How services work, the point of
all of these configuration files, symfony environments, environmental variables, and
a lot more, basically everything you'll need to really do whatever you want with
symfony. If you have any questions or ideas, we are here for you down in the comments
section below the video, Our right friends. See you next time.
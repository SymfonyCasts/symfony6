# Flex Recipes

Coming soon...

We just installed a new package. Normally When you do that, it will update composer
dot JSUN and composer dot lock, but nothing else. Well, when I ran the at status,
there are other changes. This is thanks to Flex's recipe system. Each time it
installs a new package, it checks a central repository to see if that package has a
recipe. And if it does it installs it, Where do these recipes live? The cloud, or
more specifically GitHub Check this out, run composer recipes, Which is a command
added to composer by Symfony flex. This lists all of the recipes that we have
installed in our system. And if you wanna get more information on one, you can say
composer, recipes, twig Symfony /twig bundle. This is one of the recipes that was
just executed When I hit it. You see a couple cool things. First is a nice little
tree that actually shows you which files it added to the project. It also shows you
the URL to the recipe that's installed. I'm going to click to open that. Yep. The
recipes, Symfony recipes live on a special repository called Symfony /recipes and
inside of the recipe, which is just a big directory that is organized by the
different package names I like at Symfony here, all the recipes for different Symfony
packages. So the one we were just looking at down here is twig bundle and that has
different versions of them. We're using

The latest 5.4 version. Every recipe has a manifest that JSUN file, which controls,
what it can do. The recipe system is Can only do, Can do a specific set of
operations. It can add new files to your project. And then there are various files
that it can modify. For example, this bundle section here tells Symfony flex to add
this line to our bundles dot PHP file. Lemme show you if I run get status. One of the
files that was modified was config /bundles dot PHP.

And if I look, do get dip on that file, you and see it actually added two lines
inside of here. One for each of the recipes, this config /bundles dot peach file is
not one that you need to think about much. A bundle is basically Symfony plugin. So
if you're gonna install a new bundle into your application, that gives you a bunch of
new Symfony features in order to activate that bundle, it needs to live in this line.
So the first thing that the recipe did for twig bundle thinks to this line up here is
it added this line to our bundles dot peach file, just so that we don't have to you
Two big recipes are almost like a, an automated installation readme. The second
section here is called copy from recipe. This basically says to copy the config and
templates directories from the recipe into the project. So if you look up here, uh,
those recipe, it contains a config packages, twit Yael file, And it also contains a
template /Baset H twig file Back at our terminal. If I run get status, These files,
these are the two files that you see down here. Config packages, twig, Yael,

And templates /based H two twig. And this is really, really cool. If you think about
it, when you install, Oh, I should have already said earlier that twig is a PHP ING
engine and the best choice in Symfony for rendering templates.

If you install a ING tool into your application, there's gonna have to be some
configuration somewhere that tells you what directory your templates should live in.
This is really cool. Let's go check out that config packages, twig Yamo file. We're
gonna talk about all of these Yamo files more in the next tutorial, but on a high
level, all you need to know is that configuration, this configuration file controls,
how twig behaves and check out the key in here, default path set to kernel dot
project or /templates. Now don't worry about this fancy percent syntax yet. That
basically is just a shortcut to point to the root of our project. So on a high level,
what this, it says, Hey twig, when you look for templates, you should look in the
templates directory. So in a few minutes, we're gonna start rendering templates. And
twig is going to know to look in this new templates directory and the recipe also
nicely created this directory and even gave us a new layout file, which we're gonna
use in a few minutes. So again, The point of these recipes is to give us all the code
we need so that we can immediately start using this library. The last of unexplained
file that was modified as Symfony dot lock. This is not that important. It just keeps
track of which recipes have been installed.

And you should just commit it. In fact, we wanna commit all of this stuff. So I'm
gonna say, get, add, dot, Get status. And then let's commit this Beautiful,

Further down the road. You can also update recipes. So if you're ever six months down
the road, there might be changes to those recipes. And if there are, you'll see a
little thing here that says updated available, and you can run a composer recipes,
update that package name, and it'll upgrade your code to the latest version of that
recipe. And also if you're ever playing with the library, you can also, and you
decide you don't like it. If you composer remove that library like Symfony /TWI
bundle, that's actually going to uninstall the recipe and take away all of the
configuration file that it added Pretty awesome. In fact, the recipe system is so
powerful That every single file in our project came was added via a recipe. And I'll
prove it go to github.com. Oh, nevermind. Let's back up. The other place that recipes
exist are recipes Can trip. There's not really a difference between these two. The
recipes can trip. It's a little, little bit easier to get changes into it. So it's
watched a little bit less closely. Um, but they come from both places. Yeah. Anyways,
the recipe system is so powerful that every single file on our project was originally
added via a recipe. And I'll prove it go to get up, not to /Symfony /skeleton. When
we originally ran that Symfony new command to start a project, what that really did
was clone this repository and then run composer install inside of it.

Yes, that's right. It means that our project, the one that you see right here was
originally just a composer. That JSUN file. Then when it was installing the libraries
that are inside of here, The recipes for those added everything else that you see
watch, let's go to compos. I around composer recipes again, and let's say composer
recipes. One of the libraries is called Symfony console. The recipe for Symfony
console added the bin /console file. The recipe for Symfony framework bundle. One of
the other packages that was originally installed, added almost everything else we
see, including the public /index dot PHP file. How cool is that? Okay, next we
installed twig. Let's use it to render some templates.


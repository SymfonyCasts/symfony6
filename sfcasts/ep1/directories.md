# Directories

Coming soon...

Okay. Let's get to know our new project so we can all be friends and really
understand how things work. As I mentioned, there isn't a lot here yet about 15
files, and there are really only directories that we ever need to think or worry
about. The first is public, and this is simple. This is the document route. So what
that means is that if you want a file to be publicly accessible, like an image file
or a CSS file, it needs to live in out of public. Right now, this only has one file
index dot PHP. This is called the front controller, which is a fancy word To describe
the script that is always first executed. When someone comes to your site, the job of
this file is to boot up Symfony and run our app. And now that we've looked at it,
we're never going to think open it or think about it ever again. And really other
than putting CSS or image files into public. This is not a directory that we are
going to need to deal with on a day to day basis, Which means that by kinda lied,
there are really only two directories that we need to think about on a day to day
basis, they are config and source

Config holds kittens. I wish config holds configuration files And source holds 100%
of your P P classes. We are going to spend 95% of our time inside the source
directory. Okay. So where is Symfony? Our project started with a `composer.json`
file. This lists all of the third party libraries that our app needs, the Symfony new
command that we originally ran in our, The Symfony new command that we originally ran
in our terminal Used composer, which is PhD's package manager to install these
libraries, which is just a fancy way of saying that composer downloaded these
libraries into the vendor directory Symfony itself is actually a collection, almost a
brand of many different libraries. And you can see that our app has some of them
inside the vendor /Symfony directory. Our application requires these six libraries,
but some of those libraries require other libraries. So we end up with this list of
stuff in our vendor /Symfony directory. Anyways, our new app leverages the code
inside the vendor directory to do its job. We're gonna talk more about composer and
third party apps later. But for the most part, vendor is not a directory that we need
to think about.

All right. So what about the other directories instead of here? Well, bin holds
exactly one file and will always hold just this one file. And we'll talk about what
this bin console file does in a little while The VAR directory holds cache and log
files, And is also Nothing that we're ever going to need to, to code in or think
about. We are going to live inside of config and source directories entirely. All
right, one last piece of homework. Before we dive into coding, you are free to use
whatever code editor you want. PHP storm vs, code, carrier, pigeon, whatever, but I
highly recommend PHP storm. It makes developing with Symfony a dream, and they're not
even paying me to say that though, if they do wanna start paying me, I'm cool with
that. I take payment and cookies. Part of what makes PB storm great is a plug-in
design specifically for Symfony. So I'm gonna head up to P3 storm, go to preferences
And inside of here, go to plug-ins and then marketplace and search for Symfony. Here
we go. Symfony support. So this plugin is amazing. See, it's been downloaded 5.4
million times. If you don't have it installed already, get it installed. I already
have it. This adds tons of crazy auto completion features that are specific to
Symfony

After you've installed it. Open settings and search up here for Symfony To find a new
Symfony area. The one trick about this plugin is that you need to click to enable it
for each project. So click that check box. Also it, doesn't not too important, but
change the web directory to public web is, uh, that's the name of our public
directory And then hit, okay. And we are ready next. Let's bring our app to life by
creating our very first page.


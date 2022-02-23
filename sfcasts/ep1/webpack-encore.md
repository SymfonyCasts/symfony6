# Webpack Encore

Coming soon...

Our CSF se CSS setup is fine. We put files into the public directory And then we,
Then when, and then we point to them From inside of our templates, We can add some
JavaScript files the exact same way, but if you wanna get truly serious about writing
professional CSS and JavaScript, we need to take this to the next level. And even if
you consider yourself a mostly backend developer, the tools we are about to talk
about out are going to allow you to write CSS and JavaScript that feels easier And is
less error prone than you're probably used to. The key to taking our setup to the
next level is Leveraging a node library called Webpac. This is the industry standard
tool for packaging minifying and parsing your frontend, CSS, JavaScript, and other
files, though. It can do a lot more, but setting up Webpac can be a bit tricky. And
so in the Symfony world, we use a, A lightweight tool, Which you can find in the docs
called Webpack Encore. It's still Webpack, but it just makes it easier. We have a
free tutorial About it. If you wanna take it, if you want to dive deeper,

Let's do a crash course right now, first at your command line, make sure you have
node installed. You'll also need either NPM, which comes with node automatically or
yarn. Both NPM and yarn are very similar node package managers. They're the composer
for the node world. If you use yarn, just make sure you have version one. Like I do.
All right. We're about to install a new install, a new package. So I want to kind of
clean up things. We done a lot of work, So I'm gonna say, get, add dot That looks
good. And then we'll commit everything To install Webpac Encore. It's just composer
require Encore. This installs Webpac Encore bundle. Remember bundles are like Symfony
plugins. One of the most interesting things about what we just did is the recipe that
was just installed. I'm going to run, get status. Ooh, for the first time, the recipe
modified the dot get ignore file. Let's go check that out. I'll open dot get ignore.
And you see up here is what we originally had. And down here is the new stuff added
by Webpac Encore bundle. It's ignoring this node modules directory, as you'll see in
a second node modules is basically the vendor directory for node. So if you install
some node package into your application, they go into a node modules directory, but
you don't wanna commit that

The package also added a package dot JSON file. This is nodes `composer.json`. So any
libraries that you, that you know, libraries that you need go into here. Most
importantly, we have Webpac Encore itself, which is a node library, as well as a few
other things that are gonna help us get our job done. The recipe also added an assets
directory and a configuration file to control. Webpac Encore the assets director.
We're gonna talk about in a little while, but it kind of has a bootstrapped structure
That we're going to put our code into. All right. Just like when we use composer With
composer, If you didn't have this vendor directory, you could run composer install
and it would recompose it at JSUN and download all this stuff into the vendor
directory. The same thing happens with N PM. We have a package that JSUN file to
actually download this. We can run yarn install or NPM install. This will take a few
moments to run as it downloads as it downloads. And you might get a couple warnings
like this, which you can ignore. Awesome. This did two things. This has downloaded a
bunch of files into the node modules, directory, the vendor directory for node. It
also created a yarn dot lock file, which is like composer dot Locke, both of these,
um, store, the exact versions of the PHP dependencies or no dependencies that were in
your application. It's not something you need to worry about, but both of these files
you should commit. So let's go ahead and actually do that. I'll run and get status,

Get, add dot And beautiful. Okay. Web Encore is now installed in our application, but
it's not doing anything yet. So next let's use it to take our JavaScript up to the
next level.


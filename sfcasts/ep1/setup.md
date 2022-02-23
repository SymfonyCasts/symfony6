# Setup

Coming soon...

Welcome. Hello. Hi, my name is Ryan and I have the absolute pleasure to introduce you
to the beautiful and fascinating and productive world of Symfony six. Seriously. I
feel like Willie Wonka inviting you into my factory, except with hopefully less sugar
related injuries. Anyways, if you're new to Symfony, I'm honestly a bit jealous.
You're gonna love the journey and hopefully become an even better developer along the
way. You're definitely going. You are definitely going to build some cool stuff. The
secret diss Symfony is that it starts tiny, which makes it easy to learn, but it
scales up its features automatically through a unique recipe system in 6.0, those
features include new JavaScript tools on new security system. And more Symfony is
also lightning fast with a huge focus on creating a joyful developer experience, but
without sacrificing programming and fast practices, you get to love coding and love
your code. I know that sounded cheesy, but it's true. So come with me and you'll be
in a world of pure elucidation. Nevermind. Let's just get started head over to
Symfony.com/download On this page. You're gonna have some instructions based on your
operating system to download something called the Symfony binary. This is not
actually Symfony. It's just a command line tool. That's going to help us start new
Symfony projects and give us some nice local development tools. I already have one
once you've installed this, I already have find a terminal

And you can run the command with just Symfony or to see a list of all the commands
Symfony list. There's a lot of stuff in here. The things that start with local colon
generally help with the wire developing locally. And there's also support for
deploying to the cloud. If you want to use platform DOH to deploy, but that's totally
optional what we're gonna need for this command. Most we'll learn some many of those
commands along the way. What we've written need right now is the Symfony new command.
So let's create a new project in a new directory called mixed vinyl. The name of our
project We're on that Behind the scenes. This uses composer to create the new project
more on that later. And the result is that we can move into that mixed vinyl. We can
move into that mixed vinyl directory. Cool. All right, let's open up this up in an
editor. Open this directory up in your favorite editor. I'm using PHP storm and I
highly recommend it. So what did that Symfony new command do? It boots wrapped our
very own new shiny Symfony project. Ooh. And we already have a get repository. I run
GI status. Yep. On branch main, nothing to commit.

And if I do Git log awesome. So when it started the new project, it committed all the
original files for us automatically. So I do wish we had a little bit more of an epic
commit message like OMG starting my Symfony project. What I really wanna show you
here is that the project that we just started with is super small. We can use
another, get command, get show dash name dash only to show us all the files that were
committed. And there's only about 17 here. We're gonna learn about all these files
along the way, but I really want you to feel comfortable. Like there's not a lot of
code here. Most of these are just some YAML files that we'll learn about later. We
will add more feature as we go, and it's super easy. Oh, if you did wanna start with
a bigger, more fully featured project out of the box, you can do that by rerunning.
Uh, whenever you run the Symfony new command, you can pass dash web app. That'll
install a lot more dependencies. You'll get a lot more tools. We're gonna start tiny
and add those as we go little by little by little.

All right. So before we start coding on this, let's make sure our system is set up.
One of the other commands that you have on that Symfony command is check co rec for
requirements. So hit that. If you have any problems with your setup missing PHP
extensions, that's gonna to let you know, looks like I'm good. So the final thing is
we need a web server. We have this Symfony project over here and we need to serve it
through a web server. We could have set up something like engine X, Apache, or
something cool like Cady, but for local development, this Symfony binary can help us.
You can set up a local office server with Symfony serve dash D the dash D stands for
Damon so that it will run in the background. The first time you run this, it might
ask you to run a different command, to set up an SSL certificate, which is nice
because it will then start up web server on HTTPS. All right. So let's try this. I'm
gonna copy this URL here, Spin over my browser, hold my brow and woo. This Symfony
six welcome page complete with fancy color to whenever we reload. Ooh.

So we have a Symfony six project. We have a web server that's serving it. So next
let's meet the code inside of here and demystify what this stuff does. Then we are
gonna start coding.


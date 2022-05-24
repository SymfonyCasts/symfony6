# Environments

Coming soon...

Our application is like a machine. It's a set of services. ANDP classes that do a
bunch of work and ultimately render some pages, but we can make our machine work
differently by feeding it different configuration. For example, In song controller,
we're using the logger service to log some information, But if we feed the logger,
some configuration that says log everything, it will log everything, even low level
debug messages. But if we change the config to say only log errors, Then suddenly the
same go other way. Having this controller We'll only log errors. In other words, that
sh our, the same machine that we have can behave differently based on our
configuration. And sometimes like with logging, we might need that configuration to
be different locally while we're developing versus on production to handle this.
Symfony has an important concept called environments, but I don't mean different
environments like local versus staging or beta or production and Symfony in
environment is a set of configuration.

You can run your environment in the, you can run your code in the dev environment
with a set of configuration. That's good for development, or you can run your
application in the prod environment with a set of configuration. That's good for
production here. Let me show you in the root of your directory, you have a.end file.
We're going to talk more about this file later, but see this app_an end thing. This
tells Symfony that the current environment is dev, which is perfect for local
development. When we deployed a production, we'll change this to prod and I'll show
you that in a few minutes. Okay. But what difference does that make? What happens in
our app? When we change this from dev to prod, To answer that open, Let me close some
folders, open public /index.PHP. Remember this is our front controller. This is the
first file that's executed on every request. And we don't really care much about this
File, but its job. And this function here is to basically boot up Symfony. What's
interesting is it actually reads app end value and passes it to the first argument of
this kernel class. That kernel class is actually in our code it's source /Nel.PHP. So
what I want to know is what's what is it? What is the first argument to kernel
control? So let's open this up to find absolutely nothing. This is an empty glass
that's because the majority of the logic is in this trait. So I'm going to hold, hold
command or control and click micro turn kernel trait to open that up.

The job of the kernel is to load all of the configuration and bundles in your
application.

So if you scroll down here, it has a method called configure container. Ooh, we know
what the container is now and check out what its job is. It takes this container
object and it imports config directory /packages /star.PHP or YAML. What this line is
doing is saying, Hey, container, I want you to go load all of the files from the
Kenig packages directory. So it loads all of those files. And we know that that means
that it loads those files and it passes the configuration from these to whatever
bundle is defined as the root key. So who loads all the files in the config packages
directory, this line right here, but what's really interesting for environments is
this next line. It then says import config packages /this->environment /star.YAML. If
you D if you trace to this class, you find out that this->environment is equal to the
first argument passed to kernel.

In other words, in the dev environment, this is going to be dev. So in the dev
environment, Symfony's also going to load anything in this config packages, dev
directory with this new <affirmative> what this means that we can add extra
configuration inside of this file that overrides our normal configuration. So we can
have a slightly different set of configuration in the dev environment down below
here. It also loads a file called services.YAML. And, uh, if we have it a services
underscored dev.Yama, we'll talk more about this services.Yama file a little bit
later. So if you want to add environment specific configuration, you can put it in an
environment directory or there's one other way to specify environment specific
configuration. And it's a little bit of a newer thing in Symfony. We actually saw it
at the bottom of twig YAML. It's this special when at syntax? So in Symfony by
default, there are actually three environments there's dev prod. And then if you run
tests, there's a directory called there's an environment called test. So inside of
tog.YAML, by saying, when at test, it means that this configuration here will only be
loaded. If the environment is equal to test, maybe the best example of this is in
monolog.YAML. This is the direct, the file that monolog is the bundle that controls
the logger service and check it out at the root. It does have some configuration that
is used in all environments,

But then below this, it has when at dev, now I'm not going to talk deeply about the
monolog configuration, but this con what this basically controls how the log messages
are handled. So in the dev environment, this says that it's going to log things to a
file And it uses this fancy syntax percent kernel that logs their percent. We're
going to talk about what these fancy percent strings are in a few minutes, but what
this points to is actually a VAR log dev.log file. And then the level debug means
that it's going to log every single log message to dev.log. So this is the
configuration for the dev environment. Then down here, I'll actually skip test in the
prod environment. It's going to do something very different. Again, I'm not going to
go into the specifics of what this configuration does. You can read more about it
with the logger, but the important thing here, for example, is this action level
error. It basically says, Hey, logger, You may have had many messages logged to you
in your application. Like how we use the logger here to do an info message, but I
only want you to actually log messages that are an error level or higher. So in
production, we don't want our log files filling up with tons and tons of debug
messages. So that only logs, uh, error messages.

So again, the big picture here is that we are passing different configuration to our
bundles. And so we're configuring our services differently based on the environment.
And you can even do the same thing with routes. Sometimes you have entire routes that
you only want to load in an environment background microkernel trait. If you go down,
there's also a method that's called configure routes. So this actually what's
responsible for loading your routes. And it's very similar to the other
configuration. It loads config routes /star YAML. So it loads config routes, these
YAML files, And it also loads a dev environment directory. If you have one, we don't,
it's like a route /dev, but inside of here, if you want, you can also use the when at
dev. So these, these are these two, two, this file here is actually responsible for
registering the routes used by the web DEO toolbar, but we don't want the web Devo of
our end production. Do we? That would not be good. So these routes are only actually
imported in the dev environment.

Heck even some bundles are only configured in a certain environment. If you open
config /bundles.PHP, you have the name of the bundle here. And then over here, you
have the environments. It should be enabled in. So all means all environments and
most of them are configured in all environments, but the web profiler bundle, that's
actually the bundle that gives us the web debug toolbar and profiler. It is only
loaded in the dev and test environment. So that entire bundle and the services it
provides are not loaded at all in the prod environment. So now that we understand the
basics of environments, Let's see if we can switch our application to the prod
environment. And then as a challenge, We'll configure our cache service differently
in dev versus prod. That's next.


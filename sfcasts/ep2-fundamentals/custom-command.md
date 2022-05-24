# Custom Command

Coming soon...

We have a new console command though. It doesn't do much. It basically just prints
out a message. Let's make it fancier. All right. So if you scroll to the top, this is
where we have the name of our command, and there's also a description you can give
it, which shows up next to the command. So let me change ours too. A self-aware the
command that can Do Only one thing, Our command's called app. Talk to me because what
I want to do is allow us to run the command pass in our name, and then it will say
Basically, Hey Ryan, <affirmative> So this thing here, when you have something that's
passed out of the command, that's known as an argument and that's configured down in
here in the configured method. So you see there's already an argument called a one
let's change that to Name, but this key is just internally. You would never see the
word name, show up on the command line. Like it's not name = or anything like that.
We'll just use this. When we read out that argument later, and then we can give the
argument a description

<affirmative>

And if you want, you can change the argument to be required. I'll keep it as
optional. The next thing we have our options. These are things that have a flag on
them. So what I want to have is an optional flag where I say-dash yell. And if we
have that, then we'll uppercase everything. So in this case, the name of the option
is important yell. And then here input value. None means that our flag will be
just-dash yell. Sometimes you want to have something like a-dash yell = Fu. If your
option takes a value, then you would change us to value You, change this to value
required. And let's give that a description. Beautiful. Now we're not using this
argument and option down here yet, but we can already Kind of rerun our command with
a-dash help option And awesome. We can see our description up here. You can see our
name, it's going to document how to use it and our-dash yell option.

All right. So once we call our command, very simply, Symfony's going to call execute,
and we can just do whatever we want inside of here. It passes us two arguments, an
input and an output. So if you want to read some input like the name or the option
use input, and if you want to output something, you use output. But in Symfony, we
usually immediately pop these two things into another object called Symfony style.
This is a helper class that just makes it really easy to read values in and output
values in a really nice way. So you'll see us use this IO variable along with input.
All right, so let's start by saying name = input, yet argument name. And then I'll
say if we don't have a name, Well, I'll just default to whoever you are. And then
down below this, I'm going to read out the option. I'll say should yell = input,->
get option Yell. And that will be a boolean. All right, cool. Let's clear out this
stuff down here and let's start our message. Message = Sprint F Hey percent S
exclamation point And pass in the name. Then if we want to yell,

Do you guys know what to do message Eagles SDR to upper message. Then now here we use
this IO->success, and let's put the message there. This is one of the many helper
methods on that class that will help format your output in some way. So for example,
there's also, I owe warning. I owe note that will format an output in a different
way. All right, let's try this spin over. Let's say hello to me. It works. Let's yell
at me. That works too. And we can even yell at whoever I am. Awesome. All right. So
let's go a little further. What if we need a service from inside of our command? For
example, let's say that we want to use the mix, our repository to give a Vinyl mix
recommendation. When you run our command, how can we do that? Well, we're inside of a
service and we need access to another service. We know what to do dependency
injection, Which means add the mixed repository as a constructive argument. So let's
add public function,_underscore construct, And then I'll say mix Private. So it's a
property mix, repository Mix repository. Beautiful. Now notice it's over any
construction says missing parent constructive call inside of here. Call parent

Colon, colon_underscore construct. This is a super rare situation. In fact, the only
I can think of in Symfony where the base Symfony class has its own constructor and we
need to call up. So don't forget that spot there. All right. So down here, after we
print out the success, let's print out a song recommendation, but let's make it even
cooler. What I want to do is interactively ask the user whether they want a song, a
mixed recommendation or not. We can do that by leveraging this IO object. We can say
if IO->confirm, Do you want a mixed recommendation? What that's going to do is
actually ask us that in, uh, interactively. And if we say, yes, this will return
true. If it says, no, it won't. The Iowa is full of cool stuff like this. We can ask
it a choice question. We can actually ask it just an open question. There's even a
progress bar we can build with this IO object, all kinds of cool stuff. All right. So
inside of here, let's get all of the mixes out by saying this->mixer depository
I->find all. Then I'll just do a little bit of

Ugly code here With array Rand, To get a random mix. And then we'll use IO. We'll use
another method of IO called note. I'll say I recommend the mix and then we'll pop in
mix. And then the title key off of that And done, oh, by the way, notice this re
return command success that controls the exit code of your command. So you'll want to
always have command success, um, at the bottom Of your command, or if there was some
sort of an error you could re return command error. Anyways, let's try this from the
same command, with any arguments that I want, we get the output and then we get, do
you want a mixed recommendation? Why yes we do. And what an excellent recommendation
Our, we did it. We finished what I think is the most important Symfony tutorial of
all time, no matter what you need to build in Symfony, the concepts we've just
learned will let you get your work done. For example, If you needed to add a custom
function or filter to twig, no problem, you can actually use, uh, Major bundle for
that. But even if you do it by hand, the process will feel really familiar

To what we saw with our command. You would create a new PhD class, Call it anything
you want, Make it implement whatever interface or base class That tweak extensions
need to implement. And the documentation will tell you that. And then you Just fill
in the logic, which I won't actually show you here. But again, the documentation show
you how to add a filter here. And that's it behind the scenes. Your twig extension
would automatically be seen as a service in auto configuration would make sure it was
integrated into twig In the next course, we'll put our new superpowers to work by
adding a database to our app so that we can load real dynamic data. And if you have
any real dynamic questions, we are here for you as always down in the common section.
All right, friends, Thank you so much for being with me. See you next time.


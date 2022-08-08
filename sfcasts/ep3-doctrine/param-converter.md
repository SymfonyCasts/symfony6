# Param Converter

Coming soon...

We've programmed. The happy path. When I go to /mix /13, my database does find a mix
with ID 13 and life is good, but what if I change to 99? Yikes. That is a 500 air.
Not something we want our site to ever do. This really should be a 4 0 4 air. How do
we trigger a 4 0 4 easy peas over a method? This mix object will either be a vinyl
mix, object or null if noon's not found. So we can say, if not mix, then we want to
trigger a 4 0 4. We do that by saying, throw this-> create not found exception. And
you can give this a message if you want, but this is only for developers to see,

Create, not found exception as the name suggest actually creates an exception object.
So we are throwing an exception here. That's nice cuz it means that our code after
this won't be run. Now, normally if you, you or something in your code throws an
exception, it will become a 500 error, but this is a special type of exception that
maps to a 4 0 4. Watch over here in the upper. Right? When I refresh 4 0 4 air on
production, you can customize your error pages, making a separate error page for 4 0
4 errors, 4 0 3 access denied errors or even gasp 500 errors. If something goes
really wrong, check out the Symfony docs for how to customize air pages. So cool.
We've queried for a single vinyl mix object and even handled 4 0 4 errors. But we can
do this with way less work. Check this out, replace the ID argument here with a new
argument type ended with our entity vinyl mix and then we'll call it. How about mix
to match our variable below then delete the query and also the 4 0 4. And now I don't
even need this mixed repository argument since we're not using it. All right. So this
deserves some explanation so far, the things that are allowed to be arguments to your
controller are route wild cards like Doon ID or services says,

Well, if you type an hint, an entity class Symfony will try to query for that object
automatically because we have have a wild card called ID. It will take this value. So
99 or 16, and try, try to query for a vinyl mix whose ID is equal to that value. So
if I go back and refresh it doesn't work cannot auto wire argument mix. It references
a class of vinyl mix, but no such service exists, but we know this isn't a service
it's supposed to be doing that query logic. I just told you about. So right now to
get this feature to work, you need to install another bundle though in Symfony 6.2,
this functionality should work in Symfony itself without that bundle, but it's no
problem. Find a C command line and say composer require Senseo /framework, extra
bundle, a bundle full of nice little shortcuts that are slowly being moved into
Symfony itself. This bundle will at some point soon become deprecated because you
won't need it. And now without doing anything else, it works. And if you go to a bad
ID, like 99, yes. Check it out. 4 0 4 air. This feature is called peram converter. So
you can see, it actually says object not found by peram converter annotation. We
don't actually have an app. Peram converter annotation, but it's referring to this
automatic query functionality.

So if I need to query for multiple objects like down in browse, I will use my
repository. But if I need to query just for a single object in a controller, I'll use
this trick here. All right, next, let's make it possible to up vote and down, vote
our mixes by leveraging a simple form.


# Create Service

Coming soon...

We know that bundles give us services and services do work. So if we need to write
our own custom code that does work, can we put that into our own service? Absolutely.
And that's a great way to organize your code, allow your logic to be reused and will
let you unit test your code. If you want to. We're already doing some work in our app
in the browse action. We make the HTTP request and cache. The result Putting this
logic in our controller is fine, but by moving it into its own service class, it'll
make the code more clear and reusable. So let's do it. How do we create a service in
the source directory create a new PHP class anywhere. Seriously. It doesn't matter
how you organize things. What sub directories you create. You can do whatever you
want, but pretty commonly I'll have a service directory. And inside of that, let's
create a new peach class I'll call. How about mixed repository, where repository is a
pretty common name for a, uh, service that returns data. Now I notice when I create
this peach tree storm automatically added the correct namespace. It doesn't matter
how we organize our services inside of source. As long as wherever we put it, our
namespace matches our directory.

Now, one important thing about service classes are that they, they have nothing to do
with Symfony. Our controller class is a Symfony concept, But this mixed repository is
100% a class that we are creating to organize our own logic. So there aren't any
rules. We don't need to extend a base class or implement an interface. We can make
this class look and feel however we want. So how about let's create a new public
function called find all that will return an array of all of the mixes in our system.
Back in the controller, copy all the logic that fetches the mixes and paste that
feature storm will ask you if you want to add a use statement for the cache item
interface hit. Okay. To add that on top and then instead of creating a mixes
variable, let's just return this Ya. Yes, there are some undefined variables in this
class. Don't worry about these yet. I first want to see if we can use our mixed
repository and in order to use the mixed repository inside of vinyl controller, We
need to a, We need to add it to the Symfony's container so that we can auto wire. It

Like we did with HDB client and cache interface. Well, I have a surprise spin over
your terminal and run bin console, debug auto wiring-dash all, And then scroll up to
the top. Surprise. Our mix repository is already a service in the container. So let
me explain two things. First, the-dash all flag is not that important. It basically
tells this command to show you the core services like HD client and cache, plus our
own services like mix repository second. Yes, the container somehow already saw our
repository class and recognized it as a service. How did that happen? We'll learn
about that in a few minutes for now. It's enough to know that our new mix repository
is already inside the container, which means we can auto wire it Back over in our
controller. Let's add a third argument H with mixed repository and call it. How about
mixed repository Then down here, we don't need any of this mixes code anymore. Cuz we
can simply say mixes = mix repository,-> find all how nice is that? All right. Let's
try it refresh and yes. Okay. Undefined variable coming from our mix repository, but
it works. The fact that our code got here means that when we auto wired mix
repository, the container instantiated our mix repository passed it to us so that we
could then call a method on it.

We created a service and made it available for auto wiring. Cool. But our new service
needs the HTB client and cache services in order to do its work. How could we get
those from inside of here? The answer is one of the most important concepts in
Symfony and object oriented coding in I general dependency injection. Let's talk
about that next.


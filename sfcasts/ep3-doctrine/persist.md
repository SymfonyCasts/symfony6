# Persisting to the Database

Coming soon...

Now that we have a fully functional class and the corresponding table, we are ready
to save some stuff. Okay. So how do we insert rows into the table? Answer wrong
question. We instead think about creating objects and saving them doctrine will
handle doing the insert queries for us to help do this in the simplest way possible.
Let's get a, a fake new vinyl mix page. So in my src/Controller directory, let's
actually create a new mix controller to help with this and then make this extend the
normal abstract controller. Perfect. A new public function called new that will
return a response from HT foundation. And the above is to make this a page. We'll use
the route annotation hit tab to auto complete that, and we'll call the UR /mix /new.
And just to see if this is working, I'll DD new mix. So in the real world, this page
might render a form. And then when we submit the form, create a vinyl mix object and
save it, but we'll work on stuff like that in a future tutorial. Let's just see if
this page works. So go to /mix /new and

Got it. All right. So very simply, we're just going to create a new vinyl mix object.
So I'll say mix = new VI vinyl mix. And then I'm just going to start setting some
data on this for a mix about one of my absolute favorite artists as a kid, how cookie
add some of the other fields, making sure to set the very least all of the required
properties, all, all the properties that have required columns in the database for
track count. I'll use a little randomness just to make things a little bit more fun.
And then for votes, I'll do the same thing where you can actually even have negative
votes people down vote, you rude and at the bottom, let's just DD that mix object.
So, so far, this has nothing to do with doctrine. This is just, just creating an
object and setting data on it. This data is hard coded, but you can imagine if we
submitted a form, we could read the data from the form and, and use that for here
regardless of how we do it. When we refresh, we have an object with data on it. Cool.
By the way, our empty class vinyl mix is the first class we've created. That is not a
service. Remember there are generally two types of objects. First, there are service
objects, like talk to me command or the mix repository we created in the last
tutorial. These objects do work, but they don't hold any data besides maybe some
basic config.

And we always fetch services from the container. Usually via auto wiring. The second
type of classes are data classes like vinyl mix. The primary job of these classes is
to hold data. They don't usually do any work except for maybe some basic data, mid
manipulation inside of one of its methods and unlike services. We do not fetch these
objects from the container. Instead. We just create them manually wherever, whenever
we need them. Like we just did anyways, now that we have an object, how can we save
it? Well, saving something of the database is work. So no surprise that work is done
by a service. Add an argument to your, to the method, type 10 with entity manager
interface, and let's call it entity manager, entity to manager interface is the, by
far the most important service for doctrine. We're going to use it both when we're
saving things and indirectly, when we query things to save, we call entity manager,->
persist, and pass at the object that we want to save. Then we PA also call entity
manager a flush with no arguments. Now at first that may look a little weird. Like
why do we have to call two methods? What per, when we call persist that doesn't
actually save the object. What it tells doctrine is I want you to be aware of this,
this object.

So that later when we call flush, you will save it pretty much all the time. You'll
see these two lines together, persist, and then flush. The reason it's split into two
pieces is that if you were doing some batch data loading, you could persist a hundred
mix objects and then flush them to the database all at once, which is more
performant. But most of the time you'll call persist and then flush. All right. And
then to make this a valid page, let's just return a new response object from HD
foundation and I'll use the sprint F function just so we return a little message. So
we'll say mix percent D is percent D tracks of pure eighties heaven. And for those 2%
DS, I'm going to pass that mix-> get ID and mix->out, gets track count. All right,
let's try it. Move over refresh. And yes, mix one. So check that out. We never
actually set the idea up here, which makes sense. But when we saved the database
doctrine, grab the ID and made sure that the ID property was set. So if we refresh
this more times, we get mixed 2, 3, 4, 5, and six.

Super cool. All we had to do is save the object. Doctrine's handling all the querying
stuff for us. Another way we can prove this is working is via that Symfony console
doctrine query SQL command. We can say select star from vinyl mix and this time we do
see the results. So cool. Okay. So now that we have stuff in the database, how can we
query for this? Let's do that next.


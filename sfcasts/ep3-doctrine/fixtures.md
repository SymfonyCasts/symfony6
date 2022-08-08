# Fixtures

Coming soon...

Data fixtures is the name given to dummy data that you add into your app while
developing or running tests to make life easier. It's a lot easier to work on a new
feature when you actually have a data in your database, we've created some data
fixtures in a sense via this new action, but doctrine has a system just for this

Search

For doctrine fixtures, bundle to find some documentation, find it's a again,
repository, and then you can actually read its documentation up on Symfony.com. Here
let's copy this composer require line and of our terminal and run it. Of course, ORM
fixtures is an alias in this case, two doctrine /doctrine fixtures bundle. Perfect.
I'm going to finish this run, get status, and we can see that this added a bundle and
also add a new source data fixtures directory. So let's have an AppSource data
fixtures and inside we have a single file called app fixtures. So very simply this is
a very simple bundle. It gives us a brand new command called bin console command
called doctrine:fixtures:load. When we run this command, it will empty out our
database and then execute the load method inside of app fixtures. Well, in fact, it
will execute the load method on any service we have that extends this fixture class.
So we could have multiple fixtures in this directory if we wanted to. So watch I run
it right now. It's empty in there, its our database, it loads app fixtures. And over
on the browse page we have nothing.

All right, so let's go steal our vinyl mix code FX controller. I'll copy that. And
we'll paste that here. I'll hit. Okay. To add the use statement. Now notice the load
method passes to something called the object manager. That is actually the entity
manager since we're using the ORM. So you can see down here already has the flush
that we'd expect to see. The only thing we need to have is manager->persist mix. So
it's called manager here, but these two lines are exactly what we had over in our
controller, persist and flush. So now if we go and try that command again, it EMP the
database executes our fixtures and we have one new mix. Okay. So this is kind of
cool. We have that new bin console command that helps out, but for developing, I want
a really rich set of data fixtures, like maybe 25 mixes. We could add those by hand
here or even add a loop to create them. But there's a better way via a library called
Foundry. Let's explore that next.


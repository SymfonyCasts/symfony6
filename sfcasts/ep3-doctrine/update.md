# Update

Coming soon...

After changing the vote's property, we need to make an update query to save this to
the database, to insert a mix. We use the entity manager interface service, and then
called persist and flush on it to update. We'll use that exact same service. Add a
new argument to our vote method called ented with entity entity manager interface.
I'll call it entity manager. And then very simply down here after we've set the
vote's property call entity manager->flush. That's it. Now, before I explain this,
let's make sure it's working. So I'll refresh. We have 49 votes right now. I'll hit
up. It says 50, but the real test is that when I refresh it saved as 50 that's
working. All right. So when we created a new mix earlier, we had to say persist and
pass the mix object and then flush. But now all we need is flush. Alright. So when
you call flush doctrine loops over all of the entity objects that it knows about and
saves them. If an entity hasn't been saved yet, it'll do an insert. If it has it'll
figure out what's changed and do an update. Yep. We just call flush and doctrine
figures out what to do. It's awesome. So why don't we need a persist call when we're
updating? Well, you can say entityManager error persist mix. If you want to, you just
don't need to persist tells doctrine. Hey, I want you to be aware of this object and
next time I call flush, please save it.

So when you're creating a new entity object out of ER, doctrine, doesn't really know
about that object until you call persist. But when you are updating an entity, it
means that you've already asked doctrine to query for that object. So doctrine is
already aware of it. No need to call persist. All right, now that we're saving our
mix object saved it. What now? Cuz we definitely don't want this dye statement. Well,
anytime you submit a form successfully, you always do this same thing redirect to
another page. How do we redirect in Symfony with return this->redirect, and then you
can pass it what you were able to redirect to though. Usually we're redirecting to
another page in our site, so you can use a similar shortcut called redirect to route
and then we'll get the name of the route. So let's redirect back to the show page. So
I'll copy app mix show from our show page route, and then just like the twig
argument. This takes a second argument, which is the wild cards we want to fill in.
So in this case we have an ID wild card. We'll set that to mix-> get ID.

Now remember controllers always return a response object and it turns out that a
redirect is a response. It's a response that basically says now go to this other URL.
So redirect route is just a shortcut to return a special type of response. That is
actually a redirect response to whatever this URL is here. So now I'll refresh the
page and got it down, vote up vote. This is all submitting nicely via Ajax. It works
beautifully though. It wasn't super obvious the votes change, but might be better if
we showed a success message. So let's do that next by learning about flash messages,
we're also going to make our vinyl mix entity smarter by talking about smart versus
anemic models.


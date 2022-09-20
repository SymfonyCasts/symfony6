# Updating an Entity

We *are* successfully changing the value of the `votes` property. *Now* we need
to make an update query to *save* that to the database.

To insert a `VinylMix`, we used the `EntityManagerInterface` service, and then called
`persist()` and `flush()`. To update, we'll use that *exact* same service.

## Updating an Entity with the Entity Manager

Add a new argument to the `vote()` method type-hinted with `EntityManagerInterface`.
I'll call it `$entityManager`. Then, very simply, after we've set the `votes` property
to the new value, call `$entityManager->flush()`.

[[[ code('4b5e709f1f') ]]]

That's it people! Before I explain this, let's make sure it works. Refresh. We have
49 votes right now. I'll hit up. It says 50. But the *real* proof is that when we
refresh... it *still* shows 50! It *did* save!

## Persisting and Flushing: The Details

Ok, so when we created a new `VinylMix` earlier, we had to call `persist()` - passing
the `VinylMix` object - *and* then `flush()`. But now, all we need is `flush()`. Why?

Here's the full story. When you call `flush()`, Doctrine loops over all of the entity
objects that it "knows about" and "saves" them. And that "save" is smart. If Doctrine
determines that an entity has *not* been saved yet, it will execute an INSERT query.
But if it's an object that *does* already exist in the database, Doctrine will figure
out *what* has changed on the object - if anything - and execute an `UPDATE` query.
Yep! We just call `flush()` and *Doctrine* figures out what to do. It's... the best
thing since Starburst Jellybeans.

But... why don't we need to call `persist()` when we're updating? Well, you *can*
say `$entityManager->persist($mix)` if you want to. It's just... totally redundant!

When you call `persist()`, it tells Doctrine:

> Hey! I want you to be *aware* of this object so that, next time I call `flush()`,
> you'll know to save it.

When you create a new entity object, Doctrine doesn't really *know* about that object
until you call `persist()`. But when you're *updating* an entity, it means that
you've already *asked* Doctrine to query for that object. So Doctrine is *already*
aware of it... and when we call `flush()`, Doctrine *will* - automatically - check
that object to see if any changes have been made to it.

## Redirecting to Another Page

So... we are successfully saving the new vote count to the database! Now what?
Because... I don't think this `die` statement is going to look good on production.

Well, *anytime* you submit a form successfully, you always do the same thing:
redirect to another page. How do we redirect in Symfony? With
`return $this->redirect()` passing whatever URL you want to redirect to. Though,
usually we're redirecting to another page on our site... so we use a similar
shortcut called `redirectToRoute()` and then pass a route name.

Let's redirect back to the show page. Copy the `app_mix_show` route name, paste...
and just like with the Twig `path()` function, this accepts a second argument: an
array of the route wildcards that we need to fill in. In this case, we have an
`{id}` wildcard... so pass `id` set to `$mix->getId()`.

[[[ code('755bd00a27') ]]]

Now, remember: controllers *always* return a `Response` object. And, whelp it turns
out that a redirect *is* a response. It's a response that, instead of containing HTML,
basically says:

> Please send the user to this other URL

The `redirectToRoute()` method is a shortcut that returns this special response object,
called a `RedirectResponse`.

*Anyways*, let's test the whole flow! Refresh, and... got it! After voting, we end
up right back on this page. And, thanks to Turbo, this is all happening via Ajax
calls... which is a nice bonus.

The only problem is that... it's so smooth that it's not *super* obvious that my
vote *was* actually saved - other than seeing the vote number change. It might be
better if we showed a success message. Let's do that next by learning about flash
messages. We're also going to make our `VinylMix` entity trendier by exploring the
concept of smart versus anemic models.

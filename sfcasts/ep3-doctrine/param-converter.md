# Param Converter

We've programmed the happy path. When I go to `/mix/13`, my database *does* find a mix with ID 13 and life is *good*. But what if I change this to `/99`? *Yikes*. That's a 500 error - *not* something we want our site to ever do. This really *should* be a 404 error. How do we trigger a 404? Easy peasy!

Over in our method, this `$mix` object will either be a `$VinylMix` object *or* null if one is not found. We can say `if (!$mix)`, and then, to trigger a 404, say `throw $this->createNotFoundException()`. You can give this a message if you want, but this will only be seen by developers.

This `createNotFoundException()`, as the name suggests, creates an `Exception` object. So we're actually throwing an exception here. That's nice, because it means that code after this won't be run. Normally, if you or something in your code throws an exception, it will become a 500 error, but this is a *special* type of exception that maps to a 404. Watch! Over here, in the upper right, when I refresh... 404 error! On production, you can customize your error pages, making a separate error page for 404 errors, 403 Access Denied errors, or even... *gasp* ... 500 errors if something goes *really* wrong. Check out the Symfony docs for how to customize error pages.

Okay! We've queried for a single `VinylMix` object and even handled 404 errors. But we can do this with *way* less work. Check it out! Replace the `$id` argument here with a *new* argument, typehinted with our entity, `VinylMix`, and then we'll call it... how about `$mix` to match our variable below. Then delete the query... and also the 404. And now, we don't even need this `$mixRepository` argument, since we're no longer using it.

All right, this deserves some explanation. So far, the things that are allowed to be arguments to your controller are route wildcards like `$id` *or* services. If you typehint an entity class, Symfony will try to query for that object *automatically*. Because we have have a wildcard called `$id`, it will take this value (so "99" or "16"), and try to query for a vinyl mix whose ID is *equal* to that value. If I go back and refresh... it *doesn't* work:

`Cannot autowire argument $mix of
"App\Controller\MixController::show()": it
references class "App\Entity\VinylMix" but no
such service exists.`

But we *know* this isn't a service. It's supposed to be doing that query logic I just told you about. So, to get this feature to work, you need to install another bundle. In Symfony 6.2, this functionality should work in Symfony itself without that bundle, but installing it is no problem. Find your command line and say:

```terminal
composer require sensio/framework-extra-bundle
```

This is a bundle full of nice little shortcuts that are slowly being moved into Symfony itself. This bundle will, at some point soon, become deprecated because you won't need it. And now, without doing anything else... it works! And if you go to a bad ID, like `/99`... yes! Check it out! We get a 404 error! This feature is called a "PeramConverter". You can see it says:

`App\Entity\VinylMix object not found by the @PeramConverter annotation.`

We don't actually *have* an `@PeramConverter` annotation. It's really referring to this automatic query functionality. So if I need to query for *multiple* objects, like down in `browse()`, I will use my repository. But if I need to query for a *single* object in a controller, I'll use this trick here.

Next, let's make it possible to upvote and downvote our mixes by leveraging a simple form.

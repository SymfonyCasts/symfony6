# Goodbye SensioFrameworkExtraBundle

Our app is busted: something about SensioFrameworkExtraBundle. This happened while
we were upgrading recipes. In `framework.yaml`, it's the `annotations: false`.

SensioFrameworkExtraBundle gave us all kinds of features like the `@Route`
annotation, security annotation, and something called the param converter. These all
relied on the annotation system, which has been replaced by core PHP attributes.
When we flipped them to false... the bundle didn't like it.

But hey, that's fine! All those nifty features found a new home
in the core of Symfony. So it's time to say a fond farewell to SensioFrameworkExtraBundle.

## Uninstalling it

At your terminal run:

```terminal
composer remove sensio/framework-extra-bundle
```

So long, and thanks for all the annotated fish. When it finishes... and we refresh, the
site works again!

## Checking for SensioFrameworkExtraBundle Features

But... were we using any of its features? I don't know! An easy way to check
is by running:

```terminal
git grep FrameworkExtra
```

Nope! It doesn't look like we're referencing any `use` statements directly. If
you *are*, it's just a matter of figuring out what new attribute from Symfony
replaces that feature.

To help, Symfony has a great documentation page called
[Symfony Attributes Overview](https://symfony.com/doc/current/reference/attributes.html).
This shows every PHP attribute from Symfony. For example,
SensioFrameworkExtraBundle had a `Security` annotation. Now Symfony has an `IsGranted`
attribute that you can use instead.

So if you *are* using something from the old system, find the new way and update.

## The New "Param Converter"

Though... there *is* one feature of SensioFrameworkExtraBundle that *didn't* require
an annotation... so you may have been using it without realizing. Click into one
of the mixes. Notice the URL has a `slug`. The controller for this is
`src/Controller/MixController.php`. Down here, the route *does* have a `{slug}`
wildcard... but then a `$mix` argument, which is a Doctrine entity.

Behind the scenes, the param converter would automatically query for a
`VinylMix` where `slug` equals the `{slug}` in the URL. No annotation needed:
it just worked.

The good news is that, as you can see, this magic *still* works!
The feature now lives in core. And in most cases, it will silently keep
doing its thing, just like before.

If you add an extra letter to the end of the slug to get a 404, we see that the system
behind this is `EntityValueResolver`. If you *do* need some extra control, you
can configure this with the `#[MapEntity]` attribute.

Next up: I want to upgrade to Symfony 7! But to do that, we need to remove all
these deprecations.

# The Request Object

New goal team: to allow users to upvote and downvote a mix. To accomplish this, in
the `VinylMix` entity, when a user votes, we need to send an UPDATE query to
change the `$votes` integer property in the database.

## Adding a Simple Form

Let's *first* focus on the user interface. Open `templates/mix/show.html.twig`. To
start, print `{{ mix.votesString }} votes` so we can see that here.

[[[ code('6754b906b7') ]]]

And... perfect! To add the upvote and downvote functionality, we *could* use some
fancy JavaScript. But we're going to keep it simple by adding a button that posts
a form. Well this will actually be fancier than it sounds. In the first tutorial,
we installed the Turbo JavaScript library. So even though we'll use a normal
`<form>` tag and button, Turbo will *automatically* submit it via AJAX for a
smooth experience.

By the way, Symfony *does* have a form component and we'll talk about that in a
future tutorial. But this form is going to be *so* simple that we don't really
need it anyway. Add a beautifully boring `<form>` tag with `action` set to the
`path()` function.

The form will submit to a new controller that... we still need to create!

Head over to `MixController` and add a new `public function` called `vote()`.
Give this the `#[Route()]` attribute with the URL `/mix/{id}/vote`. And because
we need to link to this, add a name: `app_mix_vote`.

[[[ code('83dd0e386e') ]]]

The `{id}` route wildcard will hold the id of the specific `VinylMix` that the
user is voting on. To query for that, use the trick we learned earlier: add an
argument type-hinted with `VinylMix` and call it `$mix`. Oh, and while we don't
*need* to, I'll add the `Response` return type. Adding this is just a good practice.

Inside, to make sure things are working, `dd($mix)`.

[[[ code('13482639eb') ]]]

Cool! Copy the name of the route, go back to the template - `show.html.twig` - and
inside `path()`, paste. And because this route has an `{id}` wildcard, pass
`id` set to `mix.id`. Also give the form `method="POST"`... because anytime that
submitting a form will *change* data on your server, it should submit with `POST`.

[[[ code('ea16ec1820') ]]]

Heck, we can even *enforce* this requirement on our route by adding
`methods: ['POST']`. That's optional, but now, if someone, for some reason, goes
directly to this URL, which is a GET request, it won't match the route. Handy!

[[[ code('6664c70b9d') ]]]

Head back over to the form. This form... will be kind of strange. Instead of having
fields the user can type into, all we need is a button. Add `<button>` with
`type="submit"`... and then some classes for styling. For the text, use a Font Awesome
icon: a `<span>` with `class="fa fa-thumbs-up"`.

[[[ code('ac3486b8ec') ]]]

Perfecto! Let's go check it out. Refresh and... thumbs up! And when we click it...
beautiful! It hits the endpoint! Notice that the URL didn't change... that's just
because Turbo submitted the form via Ajax... and then our `dd()` stopped everything.

Ok, in a minute, we're going to add another button with a thumbs down. So, somehow,
in our controller, we're going to need to figure out which button - up or down -
was just pushed.

To do that, on the button, add `name="direction"` and `value="up"`. Now, if we click
this button, it will send one piece of POST data called `direction` set to the
value `up`... almost as if the user typed the word `up` into a text field.

[[[ code('1c7b86aadc') ]]]

## Fetching the Request DAta

Ok... but how do we *read* POST data in Symfony? Whenever you need to read *anything*
from the request - like POST data, query parameters, uploaded files, or headers -
you'll need Symfony's `Request` object. And there are two ways to get it.

The first is by autowiring a service called `RequestStack`. Then you can get the
current request by saying `$requestStack->getCurrentRequest()`.

This works anywhere that you can autowire a service. But in a controller, there's
an easier way. Undo that... and instead, add an argument that is type-hinted with
`Request`. Get the one from Symfony's HttpFoundation. Let's call it `$request`.

[[[ code('4365dc9283') ]]]

At first, this looks like autowiring, right? It looks like `Request` is a service
and we're autowiring that as an argument. *But*... surprise! `Request` is *not* a
service. Nope, this is yet *another* "thing" that you're allowed to have as an argument
to your controller.

Let's review. We now know *four* different types of arguments that you can have on
a controller method. One: you can have route wildcards like `$id`. Two: You can autowire
services. Three: You can type-hint entities. And four: You can type-hint the `Request`
class. Yup, the `Request` object is *so* important that Symfony created a special
case *just* for it.

And... it's kind of beautiful. Our *whole* job as developers is to "read the incoming
request" and use it to "create a response". So it's... almost poetic that we can
have a method that takes the `Request` as an argument and returns a `Response`.
Input `Request`, *output* `Response`.

## Fetching POST Data

But I digress. There are a lot of different methods and properties on the Request
to fetch whatever you need. To read POST data, say `$request->request->get()` and
then the name of the field. In this case, `direction`.

[[[ code('8171c6f517') ]]]

We're not going to talk a lot about the `Request` object... because it's... just
a simple object that holds data. If you need to read something from it, just
look at the docs and it'll tell you how to do it.

All right, back over here, refresh the page... upvote and... got it! Okay, remove
the `dd()` and set this to a direction variable with `$direction =`.

If, for some reason, the `direction` POST data is missing (this shouldn't happen
unless someone is messing with our site), default it to `up`.

[[[ code('e208c4c9c2') ]]]

*Now* let's add the downvote. Copy the entire button... paste... change the value
to `down` and update the icon class to `fa fa-thumbs-down`.

[[[ code('43ef88914d') ]]]

Okay, we know that the value will either be `up` or `down`. In our controller,
let's use this. `if ($direction === 'up')`, then
`$mix->setVotes($mix->getVotes() + 1)`. Else, do the same thing... except it
will be `- 1`. Below, `dd($mix)`.

[[[ code('c300b69e9f') ]]]

On a real site, we'll probably also store *which* user is voting so that they
can't vote over and over again. We'll learn how to do that in a future tutorial.
But this will work just fine for now.

All right, head back and refresh. We have 49 votes. If we click the upvote
button... 50! If we refresh and click downvote... 48!

Yay! *But*, we still haven't *saved* this value to the database. When we refresh,
it always goes back to the original "49".

So... next, let's do that! We'll make an UPDATE query to the database and *also*
finish the endpoint by redirecting to another page.

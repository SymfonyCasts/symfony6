# JSON API Endpoint

In a future tutorial, we're going to create a database to manage songs, genres, and
the mixed vinyl records that our users are creating. Right now, we're working
entirely with hardcoded data... but our controllers - and - especially templates
won't feel *that* much different once we make this all dynamic.

So here's our new goal: I want to create an API endpoint that will return the data
for a single song as JSON. We're going to use this in a few minutes to bring this
play button to life. At the moment, none of these buttons do anything, but they
do look pretty.

## Creating the JSON Controller

The two steps to creating an API endpoint are... exactly the same as creating an
HTML page: we need a route and a controller. Since this API endpoint will be returning
*song* data, instead of adding *another* method inside of `VinylController`, let's
create a totally new controller class. How you organize this stuff is entirely up
to you.

Create a new PHP class called `SongController`... or `SongApiController` would also
be a good name. Inside, this will start like any other controller, by extending
`AbstractController`. Remember: that's optional... but it gives us shortcut methods
with no downside.

Next, create a `public function` called, how about, `getSong()`. Add the route...
and hit tab to auto-complete this so that PhpStorm adds the use statement on top.
Set the URL to `/api/songs/{id}`, where `id` will eventually be the database id
of the song.

And because we have a wildcard in the route, we are *allowed* to have an `$id`
argument. *Finally*, even though we don't *need* to do this, because we know that
our controller will return a `Response` object, we can set that as the return type.
Make sure to auto-complete the one from Symfony's `HttpFoundation` component.

Inside the method, to start, `dd($id)`... *just* to see if everything is
working.

[[[ code('ad23fa7738') ]]]

Let's do this! Head over to `/api/songs/5` and... got it! *Another* new page.

Back in that controller, I'm going to paste in some song data: eventually, this
will come from the database. You can copy this from the code block on this page.
Our job is to return this as JSON.

So how *do* we return JSON in Symfony? By returning a new `JsonResponse` and passing
it the data.

[[[ code('32ff0953ad') ]]]

I know... too easy! Refresh and... hello JSON! Now you *might* be thinking:

> Ryan! You've been telling us - repeatedly - that a controller must all
> always return a Symfony `Response` object, which is what `render()` returns.
> Now you're returning some *other* type of `Response` object?

Ok, *fair*... but this works because `JsonResponse` *is* a Response. Let me explain.
Sometimes it's useful to jump into core classes to see how they work. To do that,
in PHPStorm - if you're on a Mac hold command, otherwise hold control - and then
click the class name that you want to jump into. And... surprise! `JsonResponse`
*extends* `Response`. Yea, we're still returning a `Response`. But this sub-class
is nice because it automatically JSON encodes our data *and* sets the
`Content-Type` header to `application/json`.

## The ->json() Shortcut Method

Oh, and back in our controller, we can be even *lazier* by saying
`return $this->json($song)`... where `json()` is *another* shortcut method that
comes from `AbstractController`.

[[[ code('6499fe8656') ]]]

Doing this makes absolutely *no* difference because this is just a shortcut to return
... a `JsonResponse`!

If you're building a serious API, Symfony has a
`serializer` component that's really good at turning objects into JSON... and then
JSON back into objects. We talk a lot about it in our API Platform tutorial, which
is a powerful library for creating APIs in Symfony.

Next, let's learn how to make our routes smarter, like by making a wildcard only
match a *number*, instead of matching anything.

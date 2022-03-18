# Real-World Stimulus Example

Let's put Stimulus to the test. Here the goal: when we click this play icon, we're
going to make an Ajax request to our API endpoint... the one in `SongController`.
This returns the URL to where this song can be played. We'll then use that in
JavaScript to... play the song!

Take `hello_controller.js` and rename it to, how about `song-controls_controller.js`.
Inside, just to see if this is working, in `connect()`, log a message. The
`connect()` method is called whenever Stimulus sees a new matching element on
the page.

Now, over in our template, hello isn't going to work anymore, so remove that. What
I want to do is surround each song *row* with a controller.... so that's this
`song-list` element. After the class, add `{{ stimulus_controller('song-controls') }}`.

Let's try that! Refresh, check the console and... yes! It hit our code six times!
Once for *each* of these elements. Importantly, each element gets its own controller
*instance* object.

## Adding Stimulus Actions

Okay, next, when we click play, we want to run some code. To do that, we can add
an *action*. It looks like this: on the a tag, add `{{ stimulus_action() }}` - another
shortcut - and pass this the controller name that you're attaching the action to
`song-controls` and then a method inside of that controller that should be called
when someone clicks this element. How about `play`.

Cool huh? Back in our song controller, we don't need the `connect()` method anymore:
we don't need to *do* anything each time we notice another `song-list` row. Instead,
add a `play()` method.

And like with normal event listeners, this will receive an `event` object... and
then we can say `event.preventDefault()` so that our browser doesn't try to follow
the link click. to test, `console.log('Playing!')`.

Let's go see what happens! Refresh and... click. It's working. It's that easy to
hook up an event listener in Stimulus. Oh, and if you inspect this element... that
`stimulus_action()` function is just a to add a special `data-action` attribute
that Stimulus expects.

## Installing and Importing Axios

Ok, how can we make an Ajax call from inside of the `play()` method? Well, we
could use the built-in `fetch` function from JavaScript. But instead, I'm going to
install a third-party library called Axios. At your terminal, install it by saying:

```terminal
yarn add axios --dev
```

We now know what this is going to do: download this into our `node_modules` directory,
and add this line to our `package.json` file.

Oh, and side note: you absolutely *can* use jQuery inside of Stimulus. I won't
do it, but it works great - and you can install - and import - jQuery like any other
package. We talk about that in our Stimulus tutorial.

Ok, so jow do we *use* the `axios` library? By importing it!

At the top of this file, we're already importing the `Controller` base class from
`stimulus`. We can `import axio from 'axios'`. As soon as we do that, Webpack Encore
will grab the `axios` source code and *include* it in our built JavaScript files.

Now, down here, we can say `axios.get()` to make a GET request. But... what should
we pass for the URL? It needs to be something like `/api/song/5`... but how do we
know what the "id" is of *this* row?

## Stimulus Values

One of the coolest things about Stimulus is that it allows you to pass a value from
Twig *into* your Stimulus controller. To do that, declare which values you want to
*allow* to passed in via a special static property: `static values = {}`. Inside,
let's allow a `infoUrl` value to be passed in. I totally just made up that name: I'm
thinking we'll pass the *full* URL to the API endpoint in as a value. Set this
to the *type* that this will be, which will be a `String`.

We'll learn *how* we pass this value from Twig into our controller in a minute. But
because we have this, below, we can reference the value by saying `this.infoUrlValue`.

So how do we pass that value in? Back in homepage.`html.twig`, add a second
argument to `stimulus_controller()`. This is an array of the *values* you want
to pass into the controller. Pass `infoUrl` set to the URL.

Hmm, but we need to generate that URL. Does that route have a name yet? Nope!
Add `name: 'api_songs_get_one'`.

Perfect. Copy that... and back in the template, set `InfoURl` to `path()`, the
name of the route... and them an array with any wildcards. Our route route has an
`id` wildcard.

In a real app, these tracks would probably each have a database id that we could
pass. We don't have that yet... so to, kind of, fake this, I'm going to use
`loop.index`. This is a Twig magic variable: when if you're inside of a Twig `for`
loop, you access the *index* - like 1, 2, 3, 4 - by using `loop.index`. So we're
going to use this as a fake ID. Oh, and I can't forget to say `id:` then `loop.index`.

Testing time! Refresh. The first thing I want you to see is that, when we pass
`infoUrl` as the second argument to `stimulus_controller`, all that really does is
output a very special `data` attribute that Stimulus knows how to read. That's how
you pass a value into a controller.

Click one of the play links and... got it. Every controller object is passed its
correct URL.

## Making the Ajax Call

Let's celebrate by making the Ajax call! Do it with `axios.get(this.infoUrlValue)`,
yes, I just typo'ed that, `then()` and a callback using an arrow function that
will receive a `respons` argument. This will be called when the Ajax call
finishes. Log the response to start. Oh, and fix to use `this.infoUrlValue`.

Sweet! Refresh... then click a play link! Yes! It dumped the response... and
one of its keys is `data`... which contains the `url`.

Victory lap time. Back in the function, we can *play* that audio by creating a
new `Audio` object - this just a normal JavaScript object - passing it
`response.data.url`... and then calling `play()` on this.

And now... when we hit play... finally! That's music to my ears.

If you want to learn more about Stimulus - this *was* a bit fast - we have an entire
tutorial about it and it is *great*.

To finish off *this* tutorial, let's install one more JavaScript library. This one
will instantly make our entire app feel like a single page app. That's next.

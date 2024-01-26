# Modernizing with fetch() and await

This chapter isn't related to upgrading Symfony, but our *other* code - like
JavaScript - deserves to be modernized too!

## Using fetch() instead of axios

Inside of `song-controls_controller.js`, I originally used `axios` to make Ajax 
calls. I don't do that anymore. Instead, use the built-in `fetch()` function.

First, remove `axios` with:

```terminal
php bin/console importmap:remove axios
```

It's gone from `importmap.php`. Then delete the `import`... and this comment while
I'm here. Replace `axios.get()` with just `fetch`. Down here, to see if this is
working, `console.log(response)`.

Over on the browser, trigger that method by hitting the play button. And... cool!
The last two lines aren't working yet, but we see the response! It *did* make an
Ajax call. Cool!

When I originally wrote this, I used `.then` to handle the Promise. I don't often
use `.then` anymore to handle asynchronous code. Instead, I use the simpler `await`.

## Using await & async

In front of `fetch`, say `const response = await fetch()`. Then copy the inside of
the callback and put it right after.

This says: make the `fetch()` call, wait for it to finish, and *then* run this code.
It's much easier to write & read.

Though, you probably noticed my angry editor:

> the await operator can only be used in an async function.

When use `await`, we need to add `async` before the direct function that we're
inside if. I won't go into the details, but this basically advertises that *our*
function is now asynchronous. If you called it and wanted the return value, you'd
need to `await` that call as well.

But in our case, Stimulus us calling this method... and it definitely does *not*
care about any return value. So adding `async` doesn't change anything.

When we try it... the *same* result, without the callback.

So let's finish this. Add `const data = await response.json()`.

This takes the JSON from the response of our API endpoint and converts it into
an object. And yea, it's *also* an asynchronous function, so `await` comes in handy
again! Below, pass `data.url` to `Audio`.

Then celebrate, that sweet, sweet Rickroll. Modern code, no build system, life
is good.

Now that we're upgraded, let's take a tour into some of my favorite new features,
starting with some autowiring goodies that might mean you never need to touch
`services.yaml` again.

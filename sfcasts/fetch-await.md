# Modernizing with fetch() and await

This chapter isn't related to upgrading Symfony. But the rest of our code - including
JavaScript - deserves to be modernized too!

## Using fetch() instead of axios

Inside `song-controls_controller.js`, I originally used `axios` to make Ajax 
calls. I don't do that anymore. Instead, use the built-in `fetch()` function.

Remove `axios` with:

```terminal
php bin/console importmap:remove axios
```

It's gone from `importmap.php`. Then delete the `import`... and this comment while
we're here. Replace `axios.get()` with just `fetch()`. Then, to see if this is
working, `console.log(response)`.

Over in browser-land, smash the play button to trigger the method. Cool!
The last two lines aren't working, but we see the response! It *did* make an
Ajax call.

When I originally wrote this, I used `.then()` to handle the Promise. I don't often
use that anymore to handle asynchronous code. Instead, I use the simpler `await`.

## Using await & async

In front of `fetch`, say `const response = await fetch()`. Then copy the inside of
the callback and put it right after.

This says: make the `fetch()` call, wait for it to finish, and *then* run this code.
It's much simple to read and write.

Though, you probably noticed my angry editor:

> the await operator can only be used in an async function.

To use `await`, we need to add `async` before the function that we're
directly inside. I won't go into the details, but this advertises that *our*
function is now asynchronous. If you called it and wanted the return value, you'd
need to `await` that call as well.

But in our case, Stimulus is calling this method... and it definitely does *not*
care about our return value. So adding `async` doesn't change anything.

When we try it... the *same* result, without the callback.

So let's finish this: `const data = await response.json()`.

This takes the JSON from the response of our API endpoint and converts it into
an object. And yea, it's *also* an asynchronous function, so `await` comes in handy
again! Below, pass `data.url` to `Audio`.

Then celebrate, that sweet, sweet Rickroll. Modern code, no build system: life
is good.

Now that we're upgraded, let's take a tour into some of my favorite new features,
starting with autowiring goodies that might mean that you'll never edit
`services.yaml` again.

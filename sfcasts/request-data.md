# MapQueryParameter & Request Payload

Coming soon...

The next new stuffs I want to talk about are related to grabbing data from the
request. That's normally... kind of boring work. But the new features are pretty
darn cool.

# The MapQueryParameter Attribute

For example, let's add a `?query=banana` to the URL. To fetch that in our controller,
we would historically `type-hint` an argument with `Request` then grab it from there.
And while that still works, we can now add a `?string $query` argument. To tell
Symfony that this is something it should grab from a query parameter, add an attribute
in front: `#[MapQueryParameter]`.

That's it Dump the query to prove it works.

Back in the web browser world, refresh. In the web debug toolbar... got it!

## Validation from the Type-Hint

The attribute *does* also have some options. For example, if your query parameter
was called something different than your argument, you could put that here.

And beyond just grabbing the value from the request, this system *also* performs
validation. Watch: duplicate this and add an `int $page = 1` argument. Oh, and
I meant to make the `$query` argument optional so it doesn't *need* to be on the URL.
going to make the query optional also so that we don't have to have that query parameter.
Below, dump `$page`.

Ok, if we add `?page=3` to the URL... no surprise: it dumps `3`. But it *is* nice
that we get an *integer* 3: not a string. Now try `page="banana"`. We get a 404!
The system sees we have an `int` type and performs validation.

## The filter_var() Function

This entire system is handled by something called the `QueryParameterValueResolver`.
So if you really want to dig in, check that class. Internally, that class uses
a PHP function called `filter_var()` to do the validation. This is not a function
I'm very familiar with, bit it's quite powerful. On a high level, you pass it a value,
pass it one or more filters... and it tells you whether that value *satisfies* those
filters. You can also pass options to control the filters.

If you don't do anything extra, the system reads our `int` type-hint, and passes
a filter to `filter_var()` that requires it to be an `int`. That's why this fails.

## Validating an int is in a Range

But we can get fancier. Add argument called `$limit` that defaults to 0. Dump
this below. But this time, I want the limit to be between 1 and 10. To force that,
pass two options special to `filter_var`: `min_range` set to 1 and `MaxRange` set
to 10.

Let's try it! Say `?limit=3`. That works like we expect. But if we try `limit=13`,
`filter_var()` fails and we get a 404! I love that!

## Grabbing Array Query Parametrs

This can even be used to handle arrays Copy and create one more argument: an
array of `$filters` that defaults to an empty array. Dump that.

At the browser, try `?filters[]` equals banana, `&filters[]` equals apple. Check
out that array in the web debug toolbar It also works for associative arrays: add
`foo` and `bar` between the `[]`. Yup! An associative array.

It's just a really well-designed feature for fetching query parameters.

## Request Body

Also, if you need to fetch the *body* of a request, in Symfony 6.3, there's a new
method called `$request->getPayload()`. If you're building an API and your client
sends JSON in the body, `$request->getPayload()` will decode that JSON into an
associative array for you. That's nice! But also, if your user is submitting
a normal HTML form, `$request->getPayload()` works there too. It detects that
an HTML form is being submitted and decodes the `$_POST` data to an array.
So no matter if you're using an API or a normal form, you have a uniform method to
fetch the payload of the request. Small, but nice.

## MapRequestPayload

Speaking of the JSON example, it's also common to use the serializer to deserialize
the payload into an object. That relates to another new feature called
`#[MapRequestPayload]`.

In this case, `__invoke` is the controller action. This says: take the JSON from
the request and deserialize it into a `ProductReviewDto`, which is the example class
above. After sending the JSON through the serializer, it also performs validation.
So another well-thought-out feature.

Ok, that's enough for request stuff! Next up, time to test drive a new feature
in 6.4: the ability to profile console commands.

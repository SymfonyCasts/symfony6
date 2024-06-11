# MapQueryParameter & Request Payload

The next new stuffs I want to talk about are related to grabbing data from the
request. That's normally... kind of boring work. But the new features are pretty
darn cool.

# The MapQueryParameter Attribute

For example, add a `?query=banana` to the URL. To fetch that in our controller,
we would historically type-hint an argument with `Request` then grab it from there.
And while that still works, we can now add a `?string $query` argument. To tell
Symfony that this is something it should grab from a query parameter, add an attribute
in front: `#[MapQueryParameter]`.

That's it! Dump `$query` to prove it works.

[[[ code('c2c8e330d4') ]]]

Back in the web browser world, refresh. In the web debug toolbar... got it!

## Validation from the Type-Hint

The attribute *does* also have some options. For example, if your query parameter
is called something different from your argument, you could put that here.

And beyond just grabbing the value from the request, this system *also* performs
validation. Watch: duplicate this and add an `int $page = 1` argument. Oh, and
I meant to make the `$query` argument optional so it doesn't *need* to be on the URL.
Below, dump `$page`.

[[[ code('c12ac0cbc0') ]]]

Ok, if we add `?page=3` to the URL... no surprise: it dumps `3`. But it *is* nice
that we get an *integer* 3: not a string. Now try `page=banana`. A 404!
The system sees we have an `int` type and performs validation.

## The filter_var() Function

This entire system is handled by something called the `QueryParameterValueResolver`.
So if you really want to dig in, check that class. Internally, it uses
a PHP function called `filter_var()` to do the validation. This is not a function
I'm very familiar with, but it's quite powerful. You pass it a value,
one or more filters... and it tells you whether that value *satisfies* those
filters. You can also pass options to control the filters.

If you don't do anything extra, the system reads our `int` type-hint, and passes
a filter to `filter_var()` that requires it to be an `int`. That's why this fails.

## Validating an int is in a Range

But we *can* get fancier. Add an argument called `$limit` that defaults to 10. Dump
this below. But I want the limit to be between 1 and 10. To force that,
pass two options special to `filter_var`: `min_range` set to 1 and `max_range` set
to 10.

[[[ code('d9e7d8e079') ]]]

Let's try it! Say `?limit=3`. That works like we expect. But when we try `limit=13`.
`filter_var()` fails and we get a 404! I love that!

## Grabbing Array Query Parameters

This can even be used to handle arrays. Copy and create one more argument: an
array of `$filters` that defaults to an empty array. Dump that.

[[[ code('1a83b6b427') ]]]

At the browser, add `?filters[]=banana&filters[]=apple`. Check
out that array in the web debug toolbar! It also works for associative arrays: add
`foo` and `bar` between the `[]`. Yup! An associative array.

It's just a really well-designed feature for fetching query parameters.

## Request Body

Also, if you need to fetch the *body* of a request, in Symfony 6.3, there's a new
method called `$request->getPayload()`. Building an API? When your client
sends JSON in the body, use `$request->getPayload()` to decode that into an
associative array. That's nice! But also, if your user submits
a normal HTML form, `$request->getPayload()` works there too. It detects that
an HTML form is being submitted and decodes the `$_POST` data to an array.
So no matter if you're using an API or a normal form, we have a uniform method to
fetch the payload of the request. Small, but nice.

## MapRequestPayload

Speaking of JSON, it's also common to use the serializer to deserialize
the payload into an object. That relates to another new feature called
`#[MapRequestPayload]`.

In this case, `__invoke` is the controller action. This says: take the JSON from
the request and deserialize it into a `ProductReviewDto`, which is the example class
above. After sending the JSON through the serializer, it even performs validation.
So another well-thought-out feature.

Ok, that's enough for request stuff! Next up, let's test drive a new feature
in 6.4: the ability to profile console commands.

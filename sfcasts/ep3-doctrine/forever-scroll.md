# Forever Scroll with Turbo Frames

You've made it to the final chapter of the Doctrine tutorial! This chapter is... a
*total* bonus. Instead of talking about Doctrine, we're going to leverage some
JavaScript to turn this page into a "forever scroll". But don't worry! We'll talk
more about Doctrine in the next tutorial when we cover Doctrine Relations.

Here's the goal: instead of pagination *links*, I want this page to load nine results
like we see on Page 1. Then, when we scroll to the bottom, I want to make an AJAX
request to show the *next* nine results, and so on. The *result* is a "forever
scroll".

In the first tutorial in this series, we installed a library called Symfony UX Turbo,
which enabled a JavaScript package called Turbo. Turbo turns all of our link clicks
and form submits into AJAX calls, giving us a really nice single page app-like
experience without doing anything special.

Whelp, as cool as that is, Turbo has two *other*, *optional* superpowers: Turbo Frames
and Turbo Streams. You can learn all about these in our Turbo tutorial. But let's
get a quick sample of how we could leverage Turbo Frames to add forever scroll without
writing a *single* line of JavaScript.

## turbo-frame Basics!

Frames work by dividing parts of your page into separate `turbo-frame` elements,
which acts a lot like an `iframe`... if you're old enough to remember those. When
you surround something in a `<turbo-frame>`, any clicks inside of that frame will
only navigate *that* one frame.

For example, open the template for this page - `templates/vinyl/browse.html.twig` -
and scroll up to where we have our `for` loop. Add a new `turbo-frame` element right
here. The only rule of a Turbo Frame is that it needs to have a unique ID. So say
`id="mix-browse-list"`, and then go all the way to the end of that row and paste
the closing tag. And, just for my own sanity, I'm going to indent that row.

[[[ code('cc5da7cb54') ]]]

Okay, so... what does that *do*? If you refresh the page now, any navigation inside
of this frame *stays* inside the frame. Watch! If I click "2"... that *worked*. It
made an AJAX request for Page 2, our app returned that *full* HTML page - including
the header, footer and all - but then Turbo Frame found the matching `mix-browse-list`
`<turbo-frame>` *inside* of that, grabbed its contents, and put it here.

And though it's not easy to see in *this* example, the *only* part of the page that's
changing is this `<turbo-frame>` element. If I... say... messed with the title up
here on my page, and then click down here and back to Page 2... that did *not* update
that part of the page. Again, it works a lot like iframes, but without the weirdness.
You could imagine using this, for example, to power an "Edit" button that adds inline
editing.

But in our situation, this isn't very useful yet... because it works pretty much
the same as before: we click the link, we see new results. The only difference is
that clicking inside a `<turbo-frame>` didn't change the URL. So no matter what
page I'm on, if I refresh, I'm transported right back to Page 1. So this was
*kind of* a step backwards!

But stick with me. I *have* a solution, but it involves a few pieces. To start,
I'm going to make the ID *unique* to the current page. Add a `-`, and then we can
say `pager.currentPage`.

[[[ code('310c7b233c') ]]]

Next, down at the bottom, remove the Pagerfanta links and replace them with *another*
Turbo Frame. Say `{% if pager.hasNextPage %}`, and inside of it, add a
`turbo-frame`, just like above, with that same `id="mix-browse-list-{{ }}"`.
But this time, say `pager.nextPage`. Let me break this onto multiple lines here...
and then we're also going to tell it what `src` to use for that. Oh, let me fix my
typo... and then use another Pagerfanta helper called `pagerfanta_page_url` and pass
that `pager` and then `pager.nextPage`. *Finally*, add `loading="lazy"`.

[[[ code('9af460b3a9') ]]]

Woh! Lemme explain, because this is kind of wild. First, one of the super-powers
of a `<turbo-frame>` is that you can give it a `src` attribute and then leave it
empty. This tells Turbo:

> Hey! I'm going to be lazy and start this element empty... maybe because it's
> a little heavy to load. But as *soon* as this element becomes *visible* to
> the user, make an Ajax request to this URL to get its contents.

So, this `<turbo-frame>` will start empty... but as soon as we scroll down to it,
Turbo will make an AJAX request for the next page of results.

For example, if this frame is loading for page 2, the Ajax response will contain
a `<turbo-frame>` with `id="mix-browse-list-2"`. The Turbo Frame system will
grab that from the Ajax response and put it here at the bottom of our list. And if
there's a page 3, that will include yet *another* Turbo Frame down here that will
point to Page 3.

This all might seem a bit crazy, so let's try this out. I'm going to scroll up to
the top of the page, refresh and... perfect! Now scroll down here and *watch*. You
should see an AJAX request show up in the web debug toolbar. As we scroll... down
here... ah! *There's* the AJAX request! Scroll down again and... there's a *second*
AJAX request: one for Page 2 and one for Page 3. If we keep scrolling, we run out
of results and reach the bottom of the page.

If you're new to Turbo Frames, that concept may have been a little confusing, but
you can learn more on our Turbo tutorial. And a shout-out to
[an AppSignal blog post](https://blog.appsignal.com/2022/07/06/get-started-with-hotwire-in-your-ruby-on-rails-app)
that introduced this cool idea.

All right, team! Congrats on finishing the Doctrine course! I hope you're feeling
*powerful*. You should be! The only major missing part of Doctrine now is Doctrine
Relations: being able to associate one entity to another through relationships, like
many-to-one and many-to-many. We'll cover all of that in the next tutorial. Until
then, if you have any questions or have a great riddle you want to ask us, we're here
for you in the comments section. Thanks a lot, friends! And see you next time!

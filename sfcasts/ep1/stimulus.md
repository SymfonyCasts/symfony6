# Stimulus: Sensible, Beautiful JavaScript

I want to talk about Stimulus. Stimulus is a small, but delightful JavaScript library
that I *love*. And Symfony has first-class support for it. It's also heavily used
by the Ruby on Rails community.

## SPA vs "Traditional" Apps

So there are kind of two philosophies in web development. The first is where you
return HTML from your site like we've been doing on our homepage and browse page.
And then you *add* JavaScript behavior to that HTML. The second philosophy is to
use a front-end JavaScript framework to build *all* of your HTML and JavaScript.
That's a single page application.

The right solution depends on your app, but I strongly like the first approach.
And by using Stimulus - as well as another tool we'll talk about in a few minutes
called Turbo - we can create highly-interactive apps that look and feel *as*
responsive as a single page app.

We have an entire tutorial on Stimulus, but let's get a taste. You can already
see how it works in the example on their docs. You create a small JavaScript class
called a controller... and then *attach* that controller to one or more elements
on the page. And that's it! Stimulus allows you to attach event listeners -
like click events - and has other goodies.

## Stimulus Controllers in our App

In our app, when we installed Encore, it gave us a `controllers/` directory. This
is where our Stimulus controllers will live. And in `app.js`, we import
`bootstrap.js`. This is not a file that you'll need to look at much, but it's
*super* useful. This starts up Stimulus - yes, it's already installed - and
registers everything in the `controllers/` directory as a Stimulus controller.
This means that if you want to create a new Stimulus controller, all you need to
do is add a file to this `controllers/` directory!

And we get one Stimulus controller out-of-the box called `hello_controller.js`. All
Stimulus controllers follow the naming practice of something "underscore"
`controller.js` or something *dash* `controller.js`. The part before `_controller` -
so `hello` - becomes the controller's *name*.

## Attaching a Controller to an Element

Let's attach this to an element. Open up `templates/vinyl/homepage.html.twig`.
Let's see... on the main part of the page, I'm going to add a div... and
then to attach the controller to this element, add `data-controller="hello"`.

[[[ code('a818d762b0') ]]]

Let's try it! Refresh and... yes! It worked! Stimulus saw this element, instantiated
the controller... and then our code changed the content of the element. The
element that this controller is attached to is available as `this.element`.

## Stimulus Dynamically sees New Elements!

So... this is already *really* nice... because we get to work inside of a neat
JavaScript object... which is tied to a specific element.

But let me show you the *coolest* part of Stimulus: what makes it such a game changer.
Inspect element in your browser tools near the element. I'm going to modify the parent
element's HTML. Right above this - though it doesn't matter where - add *another*
element with `data-controller="hello"`.

And... boom! We see the message! *This* is the killer feature of Stimulus: you can
add these `data-controller` elements onto the page *whenever* you want. For example,
if you make an Ajax call... which adds fresh HTML to your page, Stimulus will
*notice* that and execute any controllers that the new HTML should be attached to.
If you've ever had problems where you add HTML to your page via Ajax... but that
new HTML's JavaScript is broken because it's missing some event listeners, well,
Stimulus just solved that.

## The stimulus_controller () Function

When you use Stimulus inside of Symfony, we get a few helper functions to make
life easier. So instead of writing `data-controller="hello"` by hand, we can say
`{{ stimulus_controller('hello') }}`.

[[[ code('108098d4bf') ]]]

But that's just a shortcut to render that attribute *exactly* like it was before.

Ok, now that we have the basics of Stimulus, let's use it to do something real,
like make an Ajax request when we click this play icon. That's next.

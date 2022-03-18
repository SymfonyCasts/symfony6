# Real-World Stimulus Example

Coming soon...

Everything works exactly the same. All right. Here's our goal with stimulus. When
we click this play icon, we're going to make an ajaX call to our API endpoint,

The one in song controller, Which we turns the URL to where this song can be played.
We'll then use that in JavaScript two, play that song. All right. So let's take our
hello controller here. And I'm going to rename this to How about song
controls_controller And inside, just to see if this is working, I'll log a message.
Say, I just appeared into existence. I'll even spell appeared correctly. All right.
Over in our template, uh, hello is not going to work anymore. I'll remove that in.
What I want to do is kind of surround each of these rows with a controller. So that
is going to be this song list element here. So after this, I'll say {{ stimulus
controller song-controls. Cool. Let's try that. Refresh check the console. And yes,
it hit that six times One for each of these elements. Each element gets its own
controller instance. Okay. So next step is when we click play, we want to run some
code to do that. We can add an action. It looks like this On the ATAC I'll say {{
stimulus action to use another shortcut. And once you pass here is the controller
name that you're attaching the action to song controls, and then a method inside of
that controller. That should be called when someone clicks this element.

So now, because I have play here, we can go into our song controller. We don't really
need this connect method anymore. So I'll say play And like normal event listeners.
This gets an event object, And then we can say event.prevent defaults So that it
doesn't follow the link click. And then I'm just going to say console.log() Plane.
Let's try that. Refresh and click. Yes, it's working. It's that easy to hook up an
event listener in stimulus. Oh. And if you inspect this element, that stimulus action
shortcut Is J is just a shortcut to add a special data-action attribute. All right.
How can we make an ajax call from inside of the play method? Well, we could use the
builtin fetch function from JavaScript, but instead I'm going to install a third
party library that makes axaj calls E easy called Axios. So at your terminal run,
install it by saying yarn, add axios-- we already know what this is going to do is
download that into our node modules directory, and also add this line to our package
JSON file, But we're not automatically using it. So how do we use it by importing it?

So at the top of this file, You see, we're already importing the controller base
class from stimulus. We can import Axios from Axios. As soon as we do that, Webpac
Encore is now grabbing Axios source code and including it in our JavaScript files on
the page. Now down here, we can say Axios. And one of the methods that has on it is a
git to make a git request. But what you, where else should we put here? Should we,
you know, /API/song/five, but you know, how do we know if you know, this is ID one,
or this is ID two, how do we know which1's being clicked

The way to do this? One of the cool things about stimulus is that it allows you to
pass a value from twig into your stimulus controller. The way that you do that is you
declare what values you want to allow passed in via a special static property. So you
say static values equals, and this is a stimulus thing where it needs to have this
exact name. Then inside here, you just specify which values you want to pass in. So
let's have one called info URL. I totally just made that up. And you set this to the
type that it should be. It will be a string Now because I have an info Uur URL value.
We'll learn how to pass that into the controller in a second. But because we have
that Down here, We can reference that by saying info, this.info URL value. All right.
So how do we pass that in? Well, back in homepage .HML twig, you can pass any value
as you want to a controller by passing a second argument, which is an associative
array to stimulus controller. So we'll say info URL set to, and then whatever URL we
want. Now, of course, we're not going to, hardcode the URL in here. Whenever we
reference a URL, remember what we do is we generate it. So we want to generate a URL
to our song controller route,

But to do that, we need to give it a name. So let's add name: and I'm going to call
this time. How about API_songs_ git_one.

Perfect. I'll copy that. And now over here, we can set info UR and use the path
function right here. Remember path functions, how we generate a UR URL. First
argument is the name of the route. And the second argument is any wild cards that
that route has. So our route, our wild card has an ID route that we need to pass in.
Now, a real app. These tracks inside of here would probably have database IDs that we
could pass in here. We don't actually have that yet. So to kind of fake this, I'm
going to use loop.index. This is, is a total like twig magic thing. When if you're
inside of a twig for loop, you can easily access whatever your index is like. 0 1, 2,
3, 4, by using loop.index So we're just going to use this as kind of the fake ID. Oh,
and of course I need to say ID:loop.index. All right, let's try that. Refresh the
page. The first thing I want you to see is that passing info URL as the second
argument to stimulus controller, all that really does is output a very special data
attribute that stimulus knows how to read. That's how you pass a value into your
controller. Now, want me click one of these links, got it. Every Elm, uh, controller
is past the correct URL.

So let's finally make our ajax call To do that. Axios.Git this.URL value. And then
cuz you say .then we pass it a little call back using an->function and inside of
here, I'm just going to console.log response so we can see if it's working and see
what that looks like. So refresh again, click and, And air coming from fetch. That's
my fault. This, that URL value doesn't exist. It's called info URL value. So we were
passing nothing into the git function. So lemme try that again and got it. Okay. This
object back. And one of the keys on it has on it is data with URL. So victory lap
here. We can now use some special JavaScript to play that audio const audio = new
audio. That's a special JavaScript object and I'll say response.data.URL, and then
audio.play. Finally, When I click Music to my ears, All right, if you want to learn
more about stimulus, that was kind of a fast tutorial. We have an entire tutorial
about it and it is awesome to finish off this tutorial. Let's install one more
library that will instantly transform

Our app into one that feels like a single page app. It's called turbo.

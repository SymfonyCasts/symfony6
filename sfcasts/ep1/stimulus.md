# Stimulus

Coming soon...

I wanna talk about stimulus. Stimulus is a small, but delightful JavaScript library
That I love and Symfony has first class support for it. It's a well supported tool.
That's also used by the Ruby on and rails community. So there are kind of two
philosophies on web development. The first is where you return HTML from your site
Like we've been doing on our homepage and brows page, and then you add JavaScript
behavior to those elements. And the second is to use a front-end JavaScript framework
to build all of your HTML and JavaScript. That's a single page application. The right
solution depends on your app, but I strongly like the first approach and using
stimulus and another tool we'll talk about in a few minutes called turbo. We can
create highly interactive apps that look and feel as responsive as single page apps.
We have an entire tutorial on stimulus, but let's get a taste. All right, stimulus
works like this. You can kind of see it from this example here, you create a small
class called a controller, and, and then you attach that controller to one or more
elements on your page. And that's it stimulus allows you to attach event events, to
elements like click events and a bunch of other goodies.

In our app. When we install Encore, it gave us a controller's direct. This is where
our stimulus controllers live. And in app JS, if you remember this imports, bootstrap
dot JS bootstrap dot JS, it's not a file. You'll need to look in too much, but this
is the file that's responsible for loading all files in the controllers, directory,
and registering them as stimulus else. Controllers. The point is, if you wanna create
a stimulus controller, all you need to do is pop it into this controller's directory.
And we get one out of the box called hello controller, All MIS controllers. Follow
this naming practice of hello,_controller or hello dash controller because it's named
hello controller Its name internally will be hello. Let's attach that list to an
element. Open up templates, vinyl homepage dot H two mil twig. And let's see, on the
main part of the page, I'm gonna add a div and then to attach that controller to this
element, we say data dash controller Equals. Hello. All right, let's refresh. And
yes, it worked stimulus saw that, saw this element on the page, called our
controller. And then our controller changed the content of the element, whatever
element this controller is attached to. You can access with this.element.

Lemme show you the coolest part of stimulus. What makes it such a game changer for
me? I'm gonna inspect element on the area I'm gonna modify. The parent element is
HTML and right above this doesn't matter where I'm gonna add another data controller
element right here live to the page. So data data is controller equals, hello,
closing dev, and Boom. It added another line. This is the killer feature of stimulus.
You can add these data controller elements onto the page. Whenever you want. Even for
example, via an a X call and stimulus will notice them and execute your controller.
So if you've ever had problems with, uh, adding more HTML to your page on after page
load and that HTML not having certain event listeners stimulus solves that Right now,
in reality, uh, stimulus and Symfony, we have a couple of help helper functions when
you use stimulus stimulus. So instead of writing data, dash controller = hello by
hand, we usually say it's curly, curly stimulus controller. Hello, but that's just a
shortcut to render that element. Everything works exactly the same. All right. Here's
our goal with stimulus. When we click this play icon, we're going to make an a X call
to our API endpoint,

The one in song controller, Which we turns the URL to where this song can be played.
We'll then use that in JavaScript two, play that song. All right. So let's take our
hello controller here. And I'm going to rename this to How about song
controls,_controller And inside, just to see if this is working, I'll log a message.
Say, I just appeared into existence. I'll even spell appeared correctly. All right.
Over in our template, uh, hello is not gonna work anymore. I'll remove that in. What
I wanna do is kind of surround each of these rows with a controller. So that is going
to be this song list element here. So after this, I'll say curly, curly stimulus
controller song dash controls. Cool. Let's try that. Refresh check the console. And
yes, it hit that six times One for each of these elements. Each element gets its own
controller instance. Okay. So next step is when we click play, we want to run some
code to do that. We can add an action. It looks like this On the ATAC I'll say curly,
curly stimulus action to use another shortcut. And once you pass here is the
controller name that you're attaching the action to song controls, and then a method
inside of that controller. That should be called when someone clicks this element.

So now, because I have play here, we can go into our song controller. We don't really
need this connect method anymore. So I'll say play And like normal event listeners.
This gets an event object, And then we can say event dot prevent defaults So that it
doesn't follow the link click. And then I'm just gonna say console that log Plane.
Let's try that. Refresh and click. Yes, it's working. It's that easy. Z two hook up
an event listener in stimulus. Oh. And if you inspect this element, that stimulus
action shortcut Is J is just a shortcut to add a special data dash action attribute.
All right. How can we make an a X call from inside of the play method? Well, we could
use the builtin fetch function from JavaScript, but instead I'm gonna install a third
party library that makes ax calls E easy called Axios. So at your terminal run,
install it by saying yarn, add a, as we already know what this is gonna do is
download that into our node modules directory, and also add this line to our package
JSUN file, But we're not automatically using it. So how do we use it by importing it?

So at the top of this file, You see, we're already importing the controller base
class from stimulus. We can import Axios from Axios. As soon as we do that, Webpac
Encore is now grabbing Axios source code and including it in our JavaScript files on
the page. Now down here, we can say Axios do. And one of the methods that has on it
is a get to make a get request. But what you, where else should we put here? Should
we, you know, /API /song /five, but you know, how do we know if you know, this is ID
one, or this is ID two, how do we know which one's being clicked

The way to do this? One of the cool things about stimulus is that it allows you to
pass a value from twig into your stimulus controller. The way that you do that is you
declare what values you want to allow passed in via a special property. So you say
static values equals, and this is a stimulus thing where it needs to have this exact
name. Then inside here, you just specify which values you wanna pass in. So let's
have one called info URL. I totally just made that up. And you set this to the type
that it should be. It will be a string Now because I have an info Uur URL value.
We'll learn how to pass that into the controller in a second. But because we have
that Down here, We can reference that by saying info, this.info URL value. All right.
So how do we pass that in? Well, back in homepage dot HML twig, you can pass any
value as you want to a controller by passing a second argument, which is an
associative array to stimulus controller. So we'll say info URL set to, and then
whatever URL we want. Now, of course, we're not gonna, hardcode the URL in here.
Whenever we reference a URL, remember what we do is we generate it. So we wanna
generate a URL to our song controller route,

But to do that, we need to give it a name. So let's add name Cohen, and I'm gonna
call this time. How about API_songs_get_one.

Perfect. I'll copy that. And now over here, we can set info UR and use the path
function right here. Remember path functions, how we generate a UR URL. First
argument is the name of the route. And the second argument is any wild cards that
that route has. So our route, our wild card has an ID route that we need to pass in.
Now, a real app. These tracks inside of here would probably have database IDs that we
could pass in here. We don't actually have that yet. So to kind of fake this, I'm
gonna use loop dot index. This is, is a total like twig magic thing. When if you're
inside of a twig for loop, you can easily access whatever your index is like. 0 1, 2,
3, 4, by using loop dot index. So we're just gonna use this as kind of the fake ID.
Oh, and of course I need to say ID colon loop dot index. All right, let's try that.
Refresh the page. The first thing I want you to see is that passing info URL as the
second argument to stimulus controller, all that really does is output a very special
data attribute that stimulus knows how to read. That's how you pass a value into your
controller. Now, want me click one of these links, got it. Every Elm, uh, controller
is past the correct URL.

So let's finally make our ax call To do that. Actio dot, get this.URL value. And then
cuz you say dot, then we pass it a little call back using an->function and inside of
here, I'm just going to console.log( response so we can see if it's working and see
what that looks like. So refresh again, click and, And air coming from fetch. That's
my fault. This, that URL value doesn't exist. It's called info URL value. So we were
passing nothing into the get function. So lemme try that again and got it. Okay. This
object back. And one of the keys on it has on it is data with URL. So victory lap
here. We can now use some special JavaScript to play that audio const audio = new
audio. That's a special JavaScript object and I'll say response.data dot URL, and
then audio.play. Finally, When I click Music to my ears, All right, if you want to
learn more about stimulus, that was kind of a fast tutorial. We have an entire
tutorial about it and it is awesome to finish off this tutorial. Let's install one
more library that will instantly transform

Our app into one that feels like a single page app. It's called turbo.


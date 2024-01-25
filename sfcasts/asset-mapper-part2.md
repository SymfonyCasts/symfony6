# Encore -> AssetMapper Part 2

Coming soon...

Getting these third party CSS files
is probably one of the trickier things to do inside of asset mapper. So let's focus
on bootstrap first. This is a third party package. And we install third party packages
by saying bin console import map require the package name. bootstrap is especially
interesting because it grabs the JavaScript package, a dependency of the JavaScript
package. And it also noticed that this JavaScript package commonly has a CSS file.
So it grabbed that for us to all three of these things were just added to our import
map file. Now, in reality, in my project, I'm not using the bootstrap JavaScript
at all. So I could delete this if I wanted to. But I'll leave it because it's not
hurting anything. But the real star here is this CSS file right here.  So I'm going
to copy this path, and an app.CSS, remove the top line. When importing third party
CSS with asset mapper, you can't do it from inside of your CSS file anymore, you
need to do it from inside of your app.js. So I'll say import and paste that path.
And now, got it bootstrap CSS is in there. Next up font awesome. In this case, we're
grabbing a single CSS file from a package. One of the differences between Encore
and asset mapper is when you import mapper is if you want a specific file inside
of a package, you need to import map require that specific file. So over here, run
import map require and paste that. I'll grab that one CSS file, download it into
our project and add it to import map right here. And by the way, these files are
all going into the assets vendor directory. Then we can go into app.CSS, remove that
line and add another import right there for the font awesome CSS. And that works,
though, by the way, on the topic of font awesome, I'm not importing it like this
very often anymore. I'm either using the font awesome kits that they have and just
bringing in with a little JavaScript tag on my page. Or better, I'm increasingly
using inline SVGs on our page. And hopefully you will soon have a package from Symfony
UX to help you put inline SVGs onto your site. All right, the last item in app.CSS
is a font. This is a bit trickier. If I just import map require this package, it's
going to download that packages JavaScript file. That's what import map required
does, unless you import map require something that ends in.CSS, like we did a second
ago. Now I know earlier we import map required bootstrap by itself. But that's because
I knew when I did that it was going to grab bootstrap, but it was also going to grab
that CSS file for me. But the point is, in this case, I know that I want just the
CSS file. Now with Encore, when you imported a package from a CSS file, Encore would
actually try to find the CSS file in this package for you and import that.  But that's
not going to work with asset mapper, we actually need to figure out what the path
is to the CSS file that we want inside that package. To do that, I like to go to
j s deliver.com. This is a CDN, but it's what asset mapper uses behind the scenes
to fetch its packages. So I'm going to search for that package, and it shows up.
But there's one below it called font source variable. Fonts are variable fonts are
sometimes a little bit more efficient from a file size standpoint. So when so I'm
actually going to change to that right now as well. And we click in here, check it
out actually shows me what the CSS file is inside this package. And if you wanted
a different file, for some reason, you can click files and you can kind of navigate
and find the file that you want. But I'm what I'm going to do is copy this path all
the way down to the package name, then spin over and run import bin console, import
map require and paste that, but the path is not quite right. See the version here
5.0 point one, we don't need to include that. There we go. So it's just the name
of the package. And then the file path inside the package that we want. I'll copy
that, hit Enter, it downloads that CSS file, it puts that into my import map down
here. And now finally, we can remove that from app.CSS and import it in app.js. Now
one other small change, because we changed the font variable of this in app.CSS,
the font family changes to Roboto Roboto condensed variable. Over here on our site,
watch the font right here when I refresh. Got it, it's using that new CSS file. So
that's probably one of the trickiest parts of updating it for you're using a lot
of third party CSS. Oh, and by the way, if you're using sass, there is a sympathy
cast sass bundle. So you can still use sass with asset mapper. So now that our styling
is working is our JavaScript working, we inspect element, we have an error. It's
a four or four error for something called bootstrap. That's coming from our app.js
file.  It's coming from this line right here. To fix that open app.js and add.js
to the end of it.

So when you're working with Webpack Encore, you're inside of a Node environment. And
Node kind of lets you cheat. If the file you're trying to import ends in .js, you
don't have to include the .js, you can just say ./.bootstrap. But in a real
JavaScript environment like your browser, you can't do that. You need to include the
.js on the end of it to point to the file specifically. This is probably the number
one annoying big change you'll need to make when converting. If you have a lot of
JavaScript files, you might need to add the .js onto the end of a lot of lines. All
right, so let's just walk through and see what happens next. I'll refresh. Another
error. This is a really important error. Failed to resolve module specifier at
Symfony /stimulus bridge. This means that somewhere we are importing this package
name, but that package doesn't exist in our import map.php. So put simply, when you
import a, there's two types of imports. Either an import starts with ./. or ./.,
that's called a relative import. And those are simple. It means you're importing a
file next to this file. The second type of import is called a bare import. This is
where you're importing a package or a file in a package. And when you're doing this,
the string you have inside of the import must exist in your import map file. If it
doesn't, you're going to get an error that looks like this. In this case, the error
is coming from bootstrap.js right here. See this at Symfony /stimulus bridge? That
does not live in our import map file. So the solution usually for this is to install
this third party. This usually means you're missing a third party package and you
need to install it. But in this case, this package here is actually specific to
Webpack Encore, so the fix is a little bit different. Change this to at Symfony
/stimulus bundle. And lo and behold, this is something that lives inside of our
import map. And then down here, this next line just simplifies. But that does the
exact same thing as before. This is going to start our stimulus app and load our
controllers. If you start a new Symfony application, you get all this with the
recipe.  But since we're converting, we need to do a little bit more work by hand.
All right, when we refresh now, we get the exact same error, but with a different
thing called Axios. So this means we're importing this Axios library from somewhere,
but we don't have it installed in our import map. In this case, it's coming from Song
Controls, the Song Controls controller right here. So this is a case where the fix is
to copy the package name, spin over and run bin console import map require Axios.
That puts it into our import map. And now our application's alive. This is now asset
mapper powered, no build system. However, look at our footer. The text here is darker
than it used to be. The reason for that is on X is that previously I was using
bootstrap 5.1. And when I install bootstrap, it grabbed the latest version, which is
apparently 5.3. And there's some sort of change there that kind of messed up our
styling a little bit. So of course I could figure out what changed and fix this. But
for right now, let's just change this back to the original version I was using, which
was 5.1.3. Now if we just change that and refresh, nothing's going to happen because
it still has the new version downloaded into assets vendor. To get the vendor
directory synced with import map.php, run bin console import map install. This is the
composer install. You see it noticed that we had two package changes and it
downloaded those into assets vendor. And now it's back. So we're done. We're running
on asset mapper. But if you give me one more minute, I want to modernize our code
base a little bit while we're here. Inside of our song controls controller, I
originally used axios to make Ajax calls. I don't do that anymore. I use the good old
fashioned built in fetch function. So let's remove axios, make our application
smaller and just use fetch. So run bin console import map remove axios to take that
out of our import map.php. Then I'll delete the imports and this comment while I'm
here. And replace axios get with just fetch. And then down here to kind of see if
this is working out console.log response.  Over on the browser, the way that you
trigger that method is by hitting the play button. And cool. So you see there is an
error, the last two lines aren't working correctly yet. But you can see the log for
the response object and it did work it did make an Ajax call. So that's awesome. But
as you can see, when I originally wrote this, I used.then, I don't really use.then
anymore with asynchronous code. Instead, I use a weight. So in front of fetch, say
const response = a weight fetch. And then we can copy the inside of the callback and
just put this right after it. So this says make the fetch call, wait for it to
finish, and then run this code. You can see how much easier that is to write. You
probably noticed that there is red over here, the await operator can only be used in
an async function. So when we have a weight, we do need to have async here, I'm not
going to go into the details right now, but what that does, but it's not going to
change anything in our application. In this case, we're just going to get the exact
same result as before, without the callback, which is beautiful. And now we can get
this to work by saying const data = await response.JSON. This will take the JSON from
the response, we have a little API call, it's returning JSON and convert it into an
object. This is also an asynchronous function. So await comes in handy once again,
then down here, we can just pass that into the audio URL and celebrate. Modern code
running on asynapper, no build system. Alright, so next up, let's take a tour of some
new features in Symfony 6.4. And in the new in the recent versions of Symfony 6.4.
And we're going to start with new auto wiring features that make services.yaml a file
you'll never need to touch again.

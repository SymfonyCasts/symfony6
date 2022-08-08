# Show Page

Coming soon...

If a user likes one of these mixes, they should probably be able to click into it and
go to a page to get more info. Let's add a page for that. A page to show, just one
mixin detail. However, two is src/Controller mix controller, where we already have
our new action down here. Let's add a new public function show and then add the route
above this viewer. All for this will be how about /mix /ID will be the ID of that
mixin the database. And down here, I'll add a corresponding ID argument. And just to
see if this is working, let's a D D ID cool spin over and let's go to /mix /seven and
awesome. Our route line controller are hooked up. All right. So now that we have this
ID, what we need to do is query for the vinyl mixin the database for that ID. And we
know how to query, we query via repository. So at a second argument here, type tended
with vinyl mixed repository, and let's call it mixed repository. Then we're going to
say mix = mixed repository arrow. And for the first time we're going to use the
method find, which is specifically set up to find something by its primary key so we
can pass it ID.

And then let's DD mix to make sure that's working. Now, we might, we don't know
exactly what IDs we have in our database right now. So a good workaround here for the
time being is to go to /mix /new. That creates a new mix with ID 16. In my case, I'll
put the 16 in the URL and hello vinyl mix ID 16. Notice that this returns eight vinyl
mix object, because if top course doctrine always gives us objects back by default,
which is a great,

Perfect. So now that we have the mix object, all we need to do now is render a
template, pass that end of the template and start printing out data. So we'll say
return this error render and let's call the template. How about mix /show H twig,
once again, this is mix controller. So I'm calling the directory mix. This is show
action. So show H twig that naming convention is totally optional, but you'll make
lots of friends. If you keep things nice and predictable, let's pass in a mix, a
variable called mix set to our vinyl mix object. All right, let's go create that
template templates, new directory called mixinside there. A new file called show at H
two twig, and like always pretty much every template's going to start the same way.
We're going to start by extending are base layout based at HT on that TWI. Now, as a
reminder, based on H that twig has several blocks in it, the most important block
down here is block body. That's what we'll override for our content. There is also at
the top a block title so we can control the title, the page. So let's override both
of those.

I'll say block title end block in between, I will say mix {{ mix that title, and then
the word mix. And then that's also override block body here. Then N block inside
there just to get a start of, I'll do an h1 with {{ mixed title. And now when we try
that hello page, I mean, super simple, the h1 is not even in the right spot, but it's
working now to make this page a bit more awesome. I'm going to go back to my template
and I'm actually going to paste in a bunch of new contents. You can copy all of this
from the code block on this page. The top of that is exactly the same. The extends
the same based at H O twig, the block title's the same. Uh, but then it brings in a
bunch of markup and it prints the mixed title. And then down here, I have a couple of
todos for us, printing out more details. If you refresh this nice, you see our cute
little SVG of our record here, which you probably recognize from the homepage.
Awesome. Of course, the only not awesome thing is that we have this giant SVG here
duplicated on both of those pages while we're here. Let's go ahead and fix that up.

What I'm going to do is copy all of this SVG content. Copy it. And then in how about
the mix directory? Let's create a new file called_record SVG H channel twig, and then
paste that in there. Beautiful. Now, the reason I put an_at the beginning of this
template is to indicate that it's a template partial. It's a template that doesn't
inclu doesn't include a whole page, just a part of a page. The_is totally optional
and just something that is commonly done as convention, but it doesn't actually
change the behavior by having that underscore. Now, thanks to this. We can go into
our show to H to template and we can include mix slash_record SVG.H twig, and let's
go do the same thing in the homepage template. So templates, vinyl, homepage.H twig.
This is the same SVG there. So we will also include that same template. Nice. All
right. So over on the browser homepage still looks great. And if I head back to the
mix page and refresh, that looks great too. So the last thing to get are show page
working is kind of fill in at least some details here.

So let's print on a couple of things at H two with a, with a class, and then mix that
track count songs. And that's with a little small tag where we put genre followed by
curly mix genre and with the closing parentheses, and then below this, I'll put a
palette of PTAG and we'll say mix.description, and now, awesome. This is really
starting to come to life. We don't have a track list here yet because that's another
database table. We'll need to create a future repository with the, uh, tutorial with
the relationship. But we have a really nice start here. All right. So the last
obvious thing to do here is actually to link. So when we're on the brows page, we can
click these items to get to that page. So open the templates, vinyl, browse.H channel
twig. And if we scroll down to where we loop, here's what we're going to do. Let's
change this div here that surrounds everything to an AAG.

And then I'm going to break this to multiple lines and we'll add an HF equals. And by
the way, P storm is smart enough to update the closing tag automatically for me. Now,
of course, when we link to something from twig, we use the path function. Then we
pass the name of the route right here. So what's the name of our route to our show
page? The answer is it doesn't have a name. Okay. In reality, Symfony's auto
generating one, but we don't want to rely on that. As soon as we want to link to a
route, we want to give it a proper name.

How about app_mix_show. I'll copy that head back to brows. Do H month twig and paste
that. Now the only thing that's a little bit that's unique in this case is that just
pacing the route name. Isn't going to be enough. We're going to get a giant air. An
exception has been thrown. Some mandatory parameters are missing ID to generate a URL
for app mix show. That makes perfect sense. It's trying to generate the, you were all
to this route, but we need to pass it what wild card to fill in for ID. So we do that
by passing a second argument with open curly closed curly. So it's a, like an
associate array and we'll say ID, and we'll set that to mix.id. Now the page works
and awesome. We can click any of these to hop into that particular one. All right,
next, we've got the happy path working, but what if no mix can be found for a given
ID? We need to talk about the 4 0 4 page and a way that we can query for this mixed
vinyl mix object without doing any work at all from our controller. That's next.


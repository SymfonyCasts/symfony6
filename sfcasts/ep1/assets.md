# Assets

Coming soon...

If you download the course code from the tutorial page After UN zipping, it you'll
find a start directory that contains these same brand new Symfony six app that we
created. You don't actually need this code, but it'll contain an extra tutorial
directory. Like I have here with some extra files that we are going to use. The goal
right now is to make this site look like a real site. Instead of looking like
something I designed myself And that means we need a true HTML layout that rings in
some CSS. So we know that our layout file templates based on each, and there's also a
base H TWA in the tutorial directory. And I copy that, put it in templates, Override
it and override it Before we look at that also want go ahead and also copy the three
PNG files and put those into your public directory. Beautiful. All right. Let's open
up the new base that HT twig file. There's nothing special here. We bring in some
external C SS files from some CDNs for bootstrap and font. Awesome. By the end of the
tutorial, we're actually going to use something A fancier way of bringing CSS into
our app. But for right now, this is gonna work great,

But otherwise, everything is still hard. Coded. We have a hard coded navigation area.
We still have our same block body and a hard coded footer, But let's go see what it
looks like. Refresh and woo, Not perfect, but better. The tutorial directory also
holds an app at CSS with some custom CSS to make this publicly available so that our
users browser can download it. It needs to live somewhere in the public directory,
which is our doc, but it doesn't matter where or how you organize things inside of
there. How about let's create a Styles directory, and then I'll copy that at that CSS
And paste it in there. Now back in, based on age to wick, I'll head back up to the
top. And right after I bring in all the external CSS files, let's add a link tag for
this. So link for rail = scholarship a equals. And then because the public directory
is our document route. Whenever we refer to paths, CSS, pads, or image pads, it's
going to be with respect to that directory. So the public path that this watch
/styles /app CSS, All right, refresh now, and even better.

Now, I want you to notice something so far, Symfony is not involved at all in how we
organize or use our images or CSS files. Nope. Our setup is dead simple. We put
things in the public directory. Then we refer to them with their paths, But does
Symfony have some cool features to help work with CSS and JavaScript? Absolutely.
It's called Webpac Encore And stimulus. And we'll talk about both of those towards
the end of the tutorial, But even in this simple setup where we just put files in
public and refer to them and point to them, there is one minor feed. Sure. That
Symfony offers it's called the asset function And works like this. Instead of using
/style /app CSS, we're gonna say curly curly to do, to print something with twig and
then use the asset function. And then inside quotes, move our path there. But without
the opening /so it's still relatively the public directory. You just don't include
the opening /Before we talk about that, let's just go see if it works, refresh and it
doesn't in air, but what in air unknown function did you forget to run composed
requires Symfony /asset unknown function asset. I keep telling you that Symfony
starts small and then you install things as you need them. Apparently this asset
function comes from a part of Symfony. We don't have yet, but getting it is easy.
Copy this come pose required. Command, spin over to your terminal and run it.

This is a pretty simple install. You can see it installed one package. There was no
recipes that, and there was no recipes that it added, But when we try it again, it
works. Check out the HTML source for this page. If you look at that Style sheet link,
it's HR is literally /style /app CSS. So when it finally prints the HTML here, this
is the exact same thing that we had hard coded in our template a second ago. So what
the heck is this asset function doing the answer is not much, but it is still
important. The asset function gives you two features. First, if you deploy to imagine
you deploy to a sub directory. So your Symfony app actually lives at
example.com/mixed Vinyl.

So that would, I should be our homepage. If that were the case. Then in order for our
CSS to work, the path actually has to be /mixed vinyl /style /app CSS. Anyways, if
you have the situation, the asset function will detect that automatically and add
that prefix for you without you needing to do anything, you probably don't have this
situation, but if you ever do the asset function, has you covered the second and more
important thing that the asset function does is it allows you to easily switch to a
CDN later. If you have one, Basically, Because this path is now going through the
asset function, there's a central place that we can go to with just a little bit of
configuration. We can say, Hey, Symfony, when you output this path here, I want you
to prefix it with the URL to my CDN. So then when you refresh the page instead of
/style /app CSS, it would say something like HTTPS //my cdn.com/style/app CSS. So the
asset function is really not that important, but at the same time, it gives you some
nice features. So you sh should use it.

In fact, you should use the asset function. Anytime you reference any static file,
whether it's a CSS file, job script file image, file, whatever. So for example, up
here, I'm referencing these three images. Let's use the asset function here. So I'll
say curly, curly Asset, And then I'll actually get auto completion for that. Thanks
to the Symfony plugin. We'll do the same thing down here, asset, And then one more
Early, early asset. Awesome. Of course we know that's not gonna make any difference.
And in fact, if I refresh this view source page, it will still output the exact same
pads as before. So the layout now looks good, but the content for our homepage is
just kind of kind hanging out there, looking weird. So in the tutorial directory,
let's copy the homepage.ht template and put that into our template /vinyl directory
and override it. I'll open that up once again, This new contents still extends based
on H month week. It still overrides the body block. And then it just has a bunch of
completely hard coded H team out. There's nothing dynamic in here yet. Let's go see
what it looks like on the page.

Wanna refresh. Whoa, that looks awesome. By the way, for some reason, refreshing
still showed you the old template. Just make a one character change to your template
and save, and then come back copying the file. The way that we copy the new file can
sometimes confuse Symfony's cache. All right. So this looks great. The only problem
is that it's 100% hard coded. So let's go fix that all the way up on top. Here is the
name of our record. We know that we have a title variable in here, And then also down
here for the song list, we have a bunch of just hard coded song list. Diss, let's
turn this into a loop. So right before this dip, I'm gonna say four Track and tracks
just like we had before. And then at the end of this end of track, Oh, end four. And
then for song details. And remember I'm getting all of this. This tracks variable
comes from right here in our controller. So we have a song key and an artist key. So
say curly curly track Do song A curly curly track dot artist. Beautiful. And then we
can remove all of these other song list devs all the way to the bottom.

Sweet. Let's try that. And now it's coming to life. All right, one more page to go.
Our /brows page, copy the brows dot H two month template Into the vinyl directory
into the vinyl directory. This looks a lot like our homepage. It extends based do H
two month and overrides block body. And it has a bunch of hard coded content over in
vinyl controller. We actually weren't rendering a template yet in this controller. So
let's do that now, Return this error render Final /browse that H him up twig And
let's pass in the genre. Remember we so nice if we go to /browse, we're browsing all
genres. If we put something in the wild card, we're browsing a specific genre. So I'm
going to kind of create a new genre variable That equals. If we have a slug, Then I'm
going to use This fancy code we had before Else. I'm gonna return null, And then I'll
delete the title stuff. Now we can pass this genre Into tweak Back and browse that
I'm just gonna use this on the H one. So we're either gonna be browsing a specific
genre or we're going to be browsing all genres.

So I'm gonna print something here and we actually need an if statement, you can use
that same Ary syntax to basically say if genre then use the genre else, print all
genres. All right, let's try it head over to /browse Browse. All genres looks
awesome. And then add /death model browse, death metal friends. This is starting to
feel like a real site, but right now these links up here go absolutely nowhere. Let's
fix that next by learning how to generate URLs. We're also gonna see the mega
powerful bin console command line tool.


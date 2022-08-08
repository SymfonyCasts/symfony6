# Forever Scroll

Coming soon...

You've made it to the final chapter of a doctrine tutorial. And this chapter is a
total bonus. Instead of talking about doctrine, we're going to leverage some
JavaScript to turn this page into a forever scroll, but don't worry. We'll talk more
about doctrine in the next tutorial. When we covered doctrine relations, here's the
goal. Instead of pagination links. I want this page to load nine results like we see
on page one. And then when we scroll to the bottom, I want to make an a X request to
show the next nine results and so on and so forth. The result is a forever scroll. In
the first tutorial in the series, we installed a library called Symfony UX turbo,
which enabled a JavaScript library and our application called turbo turbo turns all
of our link clicks and form submits into ax calls, giving us a really nice single
page app like experience without doing anything special. Well, turbo a actually has
other two other optional superpowers turbo frames in turbo streams. You can learn all
about these in our turbo tutorial. Let's get a quick sample of how we could leverage
turbo frames to add scroll without writing even a single line of JavaScript.

So frames work by dividing parts of our page into a turbo frame element that acts a
lot like an eye frame. When you surround something in a turbo frame, any clicking you
do inside of that turbo frame keeps the nav only nav only navigates that one frame.
For example, let's open the template for this page templates, vial browse H two twig
and scroll up. So we have a to where we have our for loop, I'm going to add a new
turbo-frame element right here. The only rule of a turbo frame is it needs to have a
unique ID. So I'll say mix browse list, and that'll go all the way to the end of that
row and paste that. And just for my own sanity, I'm going to indent that row. All
right. What does that do? Well, if you refresh the page now, any navigation inside of
this frame stays inside the frame. Watch if I click two that worked, but notice that
made an a X request for page two, it found the mix browse list, turbo frame in the ax
result and put that HTML into this frame. So it's kind of hard to tell, but literally
the only part of the page that's changing is that turbo frame.

If I like, I don't know, messed with the title up here on my page, and then click
down here and back to page two that did not update that part of the page.

Again, it works a lot like I frames, except without the weirdness, you can imagine
using this to, to power, an edit button that adds inline editing or many other
things. But in this case, this isn't too useful yet because it works pretty similar
to how it worked before we can collect these links and it shows the results. The only
difference is that terrible frames don't change the you out. So no matter what page
I'm on, if I refresh I'm right back to page one, so it's actually kind of a step
backwards, but now try this. I'm going to show you the full solution here, and then
we'll talk about it. It's a little bit tricky at first to start, I'm going to make
the ID be unique to the current page. So at a dash, and then we can say pager.current
page, then down at the bottom, I'm going to remove the pager font links and replace
them with another turbo frame. But first say if pager-has next PA, if pager.has next
page, then inside of it, we're at a turbo frame, just like the above with that same
ID mix-browse-list-this time say pager.next page.

Actually let me break this down onto multiple lines here. And then we're also going
to tell what source to use for that. Oh, lemme fix my type one has next page. And
here we can use another pager font to helper called Ponta page URL. We can pass that
pager and then pager.next page, and then finally add loading = lazy. All right. So
this is kind of nuts here. What this says is that inside of this turbo is that
whenever you have a turbo frame, one of the things you can do is add a source
attribute that tells the form, the turbo frame to load. Laly basically says you're
going to start empty. But then as soon as the page loads, you should make an a X
request to this URL to fill this in. But then when you add an extra loading = lazy to
that, it says that it should make this request for that URL. Only once the, this
turbo frame becomes visible. So when we reload the page originally in this tur frame,
just going to sit there empty. As soon as we scroll down to it, it's going to make an
AJAXrequest for the next page.

So for example, page two, the re the response of that is actually going to contain
this turbo frame, which is now going to be mixed browse list. Two turbo frame will
then steal this, put it inside that turbo frame, and then we'll have the next set of
results. And if there's still another page, it will include get another turbo frame
down here. That will point at page three. I know it's a bit crazy, but watch, let's
try this out. I'm going to scroll up to the top of the page, refresh and perfect. So
let's scroll down here and watch. You should see an H X request show up in the web
DBO toolbar as we scroll down here. Oh, you see it. You can see it popped on there,
and there is the a X request scroll down again. And then there is the second a X
request down here. You can see two, a X requests from page two and page three, then
finally we're outta results. So we get to the bottom of the page.

That was a little bit confusing. If you're new to turbo frames, it may have been a
little bit confusing. That was a pretty advanced use case of it. I saw this recently
on a site, um, and I wanted to share it with all you guys. All right, team, congrats
on finishing the doctrine. Course. I hope you're feeling powerful. You should be the
only missing part of doctrine now is doctrine relations. Being able to associate one
entity to another through relationships, like many to one and many to many we'll
cover all of that in the next tutorial until then, if you have any questions or have
a great riddle, you want to ask us, we're here for you in the comments, our friends
see you next time.


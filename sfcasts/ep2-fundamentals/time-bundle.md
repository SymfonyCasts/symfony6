# Time Bundle

On our site, you can create your own mixed vinyl. (Or you'll be able to *eventually*, at least. Right now, this button doesn't actually do anything.) But once users create mixtapes, you can go to browse the existing ones. One thing that *might* be useful to see on each individual mixtape is *when* it was posted.

If you don't remember how this page is rendered, you can actually use a trick. Down on the web debug toolbar, this shows me that the controller for this is `VinylController::browse`. Over in `src/Controller/VinylController.php`, *here* is the `browse` action. I've updated the code a *little* bit from episode one. What this does is call this array, `getMixes()`, which is just a little private function I created down here. This just creates a big array of fake data that represents the mixes that we're going to run on the page. Eventually, we'll get this from a *dynamic* source, like a database. Anyway, you can see that each mix does have a `createdAt` field. We get these mixes up in `browse`, and we pass this as the mixes variable to `vinyl/browse.html.twig`. Let's jump into that template.

Down here, you can see we use Twig's `for` loop to loop over `mixes`. Cool! Let's create the date down here as well. I'll add a `|` and another `<span>`, and then I'll say `{{ mix. }}` with the name of the key, which was `createdAt`.

There's just one problem. If you look over here, `creqatedAt` is a `DateTime` object. You can't simply *print* `DateTime` objects, and you'll get a big error reminding you of that. They *can't* be converted to a string. Fortunately, Twig has a really nice `date` filter. We talked about filters briefly in the first episode. Filters are used by adding a `|` after it, and then the name of the filter. This *particular* filter takes an argument, which is the format that you want to print the date out in. To keep things simple, I'll just put `Y-m-d`, or "year-month-day". Head over and refresh and... okay! You can see *when* these were posted, though the format isn't very attractive. We *could* do more work here to spruce this up a little, but it would be *way* cooler if we could print this out in the `ago` format. This may not sound familiar at first, but you've probably seen it before. Like when comments on a blog post, for instance, say something like "posted three months ago" or "10 minutes ago".

The question is: How can we convert a `DateTime` object into that nice `ago` format? Well, that sounds like *work* to me and, as I said earlier, *work* in Symfony is done by a service. So the *real* question is: Is there a *service* in Symfony that can convert `DateTime` objects to the `ago` format? And the answer is... no. But there *is* a third party bundle that can give us that service.

Go to `github.com/KnpLabs/KnpTimeBundle`. If you look at this bundle's documentation, it gives us a service that can do that conversion. So let's get it installed! I'll go down to the `composer require` line, copy that, spin over to our terminal, and paste. Cool! You can see that it grabbed the can the `KnpTimeBundle`. It *also* grabbed `symfony/translation`, which is a dependency of `KnpTimeBundle`. Down here, it also configured two different recipes. Let's see what those did. Run

```terminal
git status
```

and... awesome! Any time you install a third party package, that's going to update your `composer.json` and `composer.lock` files. And this also updated the `config/bundles.php` file. That's because we just installed a bundle, `KnpTimeBbundle`, and it's recipe updated it automatically. It also looks like the translation recipe added a `config` file and a `translations/` directory. We're *not* going to worry about that stuff right now.

What I really want to focus on here is the services this bundle just gave us. You could, of course, read the documentation (and you *should*), but I want to see what we can learn from exploring this by ourselves. If you remember from the last tutorial, there's a `bin/console` command. You can run

```terminal
php bin/console debug:autowiring
```

to get a list of all of the services in the system. For example, if I search for "logger", you can see there's one called `LoggerInterface`. In the first tutorial, we learned that we can auto wire *any* of the services on this list into our controller by using its type-hint. By using this `LoggerInterface` type-hint here, which is `Psr/Log/LoggerInterface`, Symfony knows to pass us this service. And then, down here, we could call methods on it like `$logger->info()`.

We installed `KnpTimeBundle` a moment ago, so let's search for "time" and... hey! Look at this! There's a new `DateTimeFormatter` service. That's from the new bundle and it's likely what we're looking for. Let's see if we can figure out how to use this in our controller. The type-hint we need is `Knp/Bundle/TimeBundle/DateTimeFormatter`, so let's go to `VinylController.php`, find `browse`, and then we'll add the new argument. By the way, the *order* of the arguments doesn't really matter, except when it comes to *optional* arguments. I made the `$slug` argument optional and you typically want your optional arguments at the *end* of your list. So I'll add `DateTimeFormatter` right here, and hit "tab" to add the use statement on top.

We can call this anything we want, so how about `$timeFormatter`. Next, I'm going to loop over the mixes. So say:

`foreach ($mixes as $key => $mix)`

Then, for each mix, let's add a new `ago` key. Say:

`$mixes[$key]['ago'] =`

And this is where we're going to put that new `ago` formatter. How do we use this `TimeFormatter`? I have no idea! But we have its type-hint here, so I bet PhpStorm will help us figure that out. Say `$timeFormatter->`, and you can see the four possible methods on it.

The one *we* want is `formatDiff()`, and we'll give it the "from" time, which is `$mix['createdAt']`. And that's *all* we need! What I'm doing here is looping over these `$mixes` down here, taking this `createdAt` key, which is a `DateTime` object, *giving* it to the `formatDiff()` function, and *that* will return the string `ago` format. To see if this is working like it should, below, I'm just going to `dd($mixes)`.

Okay, let's see if we've implemented this new service correctly. Spin over, refresh... and let's open it up. Yes! Look at that: `"ago" => "7 months ago"`... `"ago" => "18 days ago"`... It *works*. Sweet!

Now that our mixes all have a new `ago` field on them, in `browse.html.twig`, we can remove the `mix.createdAt|date('Y-m-d')` and replace it with `mix.ago`. And now... *much* better!

So we had a problem, and *knew* it needed to be solved by a service because services do work. We didn't have a service that did what we needed *yet*, so we went out, found one, and installed it. Problem *solved*! Symfony itself has a *ton* of different packages, and each of them give you several services. But sometimes, you'll want third party bundles like this one to get the job done. And typically, you can just search online for the problem you're trying to solve, plus "Symfony bundle", to find it.

In the case of this *specific* bundle, in addition to the nice `DateTimeFormatter` service that we're using in our controller, this bundle *also* added a custom Twig filter. That's right! You can add custom functions and filters to Twig. Check this out! Another useful `bin/console` command is

```terminal
php bin/console debug:twig
```

This gives you a list of all of the functions, filters, and tests in Twig, along with the *one* global Twig variable we have. If you go up to Filters, there's a new one called "ago". That wasn't there before we installed `KnpTimeBundle`, but it *is* there now.

So, all of the work we did in our controller is perfectly fine - we just didn't *need* to do it when a bundle can do it for us. I'm going to delete the `foreach`... remove the `DateTimeFormatter` service... and even though I don't *need* to, I'll remove the use statement on top to really clean things up.

In `browse.html.twig`, we don't have an `ago` field anymore. We still have a `createdAt` field though, which is that `DateTime` object. Instead of piping this to the `date` filter like we did before, we'll pipe it to `ago`. And that's it! Back over on our site, when I refresh... we get the *exact* same result. By the way, we won't do it in this tutorial, but by the end, you'll be able to easily follow the documentation to create your own custom Twig functions and filters.

Next, as you already know, our app does *not* have a database yet. Let's add one! But to make things more interesting, I want to make an API call from our code to a GitHub repository and use *that* as our mixtape data source.

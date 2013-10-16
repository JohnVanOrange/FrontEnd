---
layout: post
title: "Generating Random IDs"
description: ""
category: ""
tags: ['id', 'url']
---
{% include JB/setup %}

On John VanOrange, all images have a simple six character name that uniquely identifies the image. In the early days, this was not the case and there were two other methods for uniquely identifying the images.

###History

At first, the images all had incremental numeric IDs.  This was done for simplicity sake. In the database, it's easy to setup an auto incrementing column and let the database automatically handle assigning IDs to each image.  While this is the simplest method, it has the drawback that data can be easily scraped from the database using APIs as the APIs can be called in a loop and the image ID can be incremented.  If you don't want people to have the ability to load data on every single image in the database, it's helpful to add a layer of security by obscurity and don't have the IDs be that easily guessable.
Another good identifying method for images is to use a hash of the image.  Since the site shouldn't be storing multiple copies of the same image, storing a MD5 hash of the image in the database and using that as a unique ID works well.  This makes the image ID non-incremental, and it has the added advantage of making it so that you can identify if an image already exists or not. John VanOrange still stores this info for that purpose.  The downside to using MD5 hashes as identifiers is that the ID is now 32 characters long.  This is not ideal when using the ID as part of a URL.

###Present

At this point, there is now a random six character ID stored for each image. This is a decent middle ground between having the IDs be random enough that they are hard to guess, but also short enough that they fit well inside a URL. One thing that had to be considered was that I needed there to be enough values available, that this setup would last for the foreseeable future.  To make the IDs URL friendly, I've limited the characters used to just the 26 alphabetical characters in both lower and upper case (these are case sensitive IDs) and the digits 0 through 9.  This gives 62 total characters.  With six characters in the ID, the total available number of images is 62 ^ 6, or around 56.8 billion images.  For the purpose of John VanOrange, this is plenty.  It might not work for a site like Google, but if there was a seven character added, there would be a total of 3.5 trillion IDs available.

###Implementation

Generating the IDs is fairly straightforward.

<script src="https://gist.github.com/cbulock/7014470.js"> </script>

The downside to this method is it requires doing a database looking to verify the uniqueness of the ID after generating it.

<script src="https://gist.github.com/cbulock/7014405.js"> </script>

That's essentially all there is to generating the IDs used for the images on John VanOrange.

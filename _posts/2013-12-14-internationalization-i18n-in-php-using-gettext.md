---
layout: post
title: "Internationalization (i18n) in PHP using Gettext"
description: ""
category: ""
tags: []
---
{% include JB/setup %}

John VanOrange doesn't currently have a large international audience, but at the same time, there has slowly been an increase in traffic from non-English speaking users. Since the site is primarily based on images, the language probably isn't a huge factor on the usability. However, I'm sure people would prefer to use an interface in their native language.  So I started on a project to add the ability to display the interface in multiple languages.  It's not as difficult as you may think, but it does require a number of pieces to make it work.

For some background, here is the currently running setup that John VanOrange is running:

* Apache 2.2
* PHP 5.4
* Twig template engine

I choose <a href='http://php.net/gettext'>gettext</a> as the method of implementing translations for the interface due to the fact that it is widely supported and there is a lot of documentation surrounding it. It does require a PHP extension to work, the PHP docs have info on that. It's quite possible that it's already installed on your server.  To check to see if it is already setup on your server, you can run :

    php -i | grep gettext

###Getting the App Prepared

To use gettext, you have to set up all the strings in your app to be read into gettext. Since I'm using Twig, this requires two different methods, depending on whether the string is in straight PHP, or if it's in a template.

PHP is easy, just change `"string"` to `_("string")` everywhere in your code. 
The `_()` function is an alias to `gettext()`.  The nice thing about this is that gettext will echo the string back if there are no translations setup yet.  So you can do this before you do any of the actual translation work.
Twig requires placing the strings in a `{% raw %}{% trans "string" %}{% endraw %}` wrapper. There is also a Twig i18n extension that needs to be enabled. <a href='http://twig.sensiolabs.org/doc/extensions/i18n.html'>They have documentation on how to do that.</a>  One thing the Twig documentation is missing is that the twig/extensions package needs to be installed to enable that extension.  Just adding it to the composer.json file and running `composer install` will get it setup.

Ultimately, this just generates the same `_()` functions from the templates, so adding the trans tags is safe to do at any point.

For John VanOrange, I had to go through all my .twig files and add trans tags. I also had to go through all the API and wrap API response messages and exception messages in `_()`.

###Creating Translation Catalog Files

Once all the code is ready for translation, there are two types of files that will be required to work with gettext to enable the translating.  First, a POT file that is a template for the translation files.  And then PO files containing the actual strings.

The POT file is created by extracting all the strings from your code.  There is a command-line tool called `xgettext` to automate this. However, that only works with actual code, not Twig templates.  To work with Twig, it's easiest to first output all the templates into actual code and then scan those files.  The Twig docs have <a href='http://twig.sensiolabs.org/doc/extensions/i18n.html#extracting-template-strings'>an example</a> on how to do that.
I wrote a quick script that scans runs the Twig extraction, and then scans through the Twig cache files and the API and pages code on John VanOrange to find strings, and then output that to a messages.pot file in the locale directory.  This is the script:

<script src="https://gist.github.com/cbulock/7952487.js"> </script>

Once you have the POT file, there is a GUI tool called <a href='http://www.poedit.net/'>POEdit</a> that does the rest.

With POEdit open, you can simply go to `File > New Catalog from POT file` and it will set up a new catalog.  A catalog will be the specific translation for a language.  I created a `locale` directory for the translations.  Inside that directory, there should be directories for each locale, such as `de_DE` and `fr`.  Inside those directories, there needs to be an `LC_MESSAGES` directory. Inside of that will be the catalog which should be named something like `messages.po`.  The structure will look like this:

    locale/
    ├── de_DE
    │   └── LC_MESSAGES
    │       ├── messages.mo
    │       └── messages.po
    ├── es_ES
    │   └── LC_MESSAGES
    │       ├── messages.mo
    │       └── messages.po
    ├── fr
    │   └── LC_MESSAGES
    │       ├── messages.mo
    │       └── messages.po
    ├── messages.pot
    └── zh_CN
        └── LC_MESSAGES
            ├── messages.mo
            └── messages.po

The `messages.mo` files are automatically created by POEdit.

Now that the catalog is created, it's just a task of going through and entering in the translated strings.  The great thing about POEdit is that any time new strings are added to your app, you can re-run xgettext and recreate the POT file.  Then, just open a catalog, and go to `Catalog > Update from POT file` and the new strings will be added to the catalog.

###Getting the Correct Language

At this point, all that is left is telling the app what locale needs to be used.  There are a number of ways to accomplish this, from having a dropdown selector, to using different domains for different languages.  However, browsers send an `Accept-Language` header on every request telling the server the prefered language for the user.  May as well just use that information. For this, I used a browser detection package "<a href='https://github.com/gavroche/php-browser'>gavroche/browser</a>" which can be installed through composer.  I like this as I already had some of my own code that was detecting the browser itself, and this detects not only the browser, but the language as well as other information.

I finally inserted the following into my apps router to add the proper language for gettext to use:

<script src="https://gist.github.com/cbulock/7952691.js"> </script>

With that in place, everything should work automatically. 

This is what my composer.json requires section looks like after all of this:

<script src="https://gist.github.com/cbulock/7951635.js"> </script>

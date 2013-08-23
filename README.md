This is the codebase for the image viewing site at http://johnvanorange.com

There are three software requirements to setup the repo:
 * Composer - http://getcomposer.org/
 * Node.js - http://nodejs.org/download/
 * Bower - http://twitter.github.com/bower/

To setup this repo, do the following:
 * A MySQL database needs to be setup.
 * The settings.inc.default file needs to be renamed to settings.inc and the correct database and various settings input
 * In the tools directory run "php dbimport.php ../sql/tables.sql" to setup the database
 * Install ImageMagick: "pear config-set preferred_state beta" and then "pecl install imagick"
 * Run "composer install --prefer-source" to install PHP dependencies
 * Run "bower install" to install Javascript dependencies
 * /media/ and /media/thumbs/ must be writable by apache.

**Automated Testing Status**

[![Build Status](https://travis-ci.org/cbulock/JohnVanOrange.png?branch=master)](https://travis-ci.org/cbulock/JohnVanOrange)

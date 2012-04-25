This is the codebase for the image viewing site at http://johnvanorange.com

To setup this repo, do the following:
 * All the images must go into a "media" directory in the root.
 * A /smarty/templates_c directory needs to be created and needs to be writable by apache.
 * A MySQL database needs to be created.  The sql directory contains the query to create the required structure
 * The settings.inc.default file needs to be renamed to settings.inc and the correct database and various settings input
 * The tools directory has a script that will import all the images into the database

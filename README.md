This is the codebase for the image viewing site at http://johnvanorange.com

To setup this repo, do the following:
 * Run "composer install" to install dependencies
 * /templates_c directory needs to be created in the webroot.
 * /templates_c/, /media/, /media/thumbs/, /upload-handler/files/, and /upload-handler/thumbnails/ must be writable by apache.
 * A MySQL database needs to be created.  The sql directory contains the query to create the required structure
 * The settings.inc.default file needs to be renamed to settings.inc and the correct database and various settings input

This also requires that the Imagick extension be setup in PHP.
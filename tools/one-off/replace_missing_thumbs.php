<?php

require_once('../../vendor/autoload.php');
require_once('../../settings.inc');
require_once('../../common/exceptions.php');

$thumbs = explode("\n",file_get_contents('missing.thumbs'));

foreach ($thumbs as $name) {

 $width = '240';
 $height = '160';

 try {
  $image = new Imagick(ROOT_DIR.'/media/'.$name);

  foreach ($image as $frame) {
   $frame->thumbnailImage($width,$height,TRUE);
  }

  file_put_contents(ROOT_DIR.'/media/thumbs/'.$name,$image->getImagesBlob());
  echo "Created thumb for ". $name . "\n";
 }
 catch(ImagickException $e) {
  echo "An exception occured for " . $name . " " . $e->getMessage();
 }
}
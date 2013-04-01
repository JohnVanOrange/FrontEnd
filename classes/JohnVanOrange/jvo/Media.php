<?php
namespace JohnVanOrange\jvo;
use Exception;

class Media extends Base {
 
 private $image;

 public function __construct() {
  parent::__construct();
  $this->image = new Image;
 }
 
 
 public function get($image) {
  $imagedata = $this->image->get($image);
  $image = new Imagick(ROOT_DIR.'/media/'.$imagedata['filename']);
  return $image;
 }
 
 public function scale($image, $width = 240, $height = 160) {
  $imagedata = $this->image->get($image);
  $image = new Imagick(ROOT_DIR.'/media/'.$imagedata['filename']);

  foreach ($image as $frame) {
   $frame->thumbnailImage($width,$height,TRUE);
  }

  return $image; 
 }
 
 public function watermark($image) {
  $imagedata = $this->image->get($image);
  
  if ($imagedata['type'] == 'gif') return $this->get($image);
  
  $image = new Imagick(ROOT_DIR.'/media/'.$imagedata['filename']);

  $watermark = new Imagick(ROOT_DIR.'/img/'.WATERMARK);

  $iWidth = $image->getImageWidth();
  $iHeight = $image->getImageHeight();
  
  $low = $iWidth;
  if ($iHeight < $iWidth) $low = $iHeight;

  $watermark->scaleImage($low * .35, 0);

  $wWidth = $watermark->getImageWidth();
  $wHeight = $watermark->getImageHeight();

  //watermark position
  $x = 3;
  $y = ($iHeight - $wHeight) - 1;

  foreach ($image as $frame) {
   $frame->compositeImage($watermark, imagick::COMPOSITE_OVER, $x, $y);
  }

  return $image;
 }
 
}
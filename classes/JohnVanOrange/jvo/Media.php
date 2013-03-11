<?php
namespace JohnVanOrange\jvo;
use Exception;

class Media extends Base {
 
 private $image;

 public function __construct($options=array()) {
  parent::__construct();
  $this->image = new Image;
 }
 
 
 public function get($options=array()) {
  $imagedata = $this->image->get(array('image'=>$options['image']));
  $image = new Imagick(ROOT_DIR.'/media/'.$imagedata['filename']);
  return $image;
 }
 
 public function scale($options=array()) {
  if (!$options['width']) $options['width'] = '240';
  if (!$options['height']) $options['height'] = '160';
  $imagedata = $this->image->get(array('image'=>$options['image']));
  $image = new Imagick(ROOT_DIR.'/media/'.$imagedata['filename']);

  foreach ($image as $frame) {
   $frame->thumbnailImage($options['width'],$options['height'],TRUE);
  }

  return $image; 
 }
 
 public function watermark($options=array()) {
  $imagedata = $this->image->get(array('image'=>$options['image']));
  
  if ($imagedata['type'] == 'gif') return $this->get($options);
  
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
<?php
namespace JohnVanOrange\jvo;

class Media extends Base {
 
 public function __construct() {
  parent::__construct();
 }
 
 /**
  * Add media
  *
  * Add media associated with an image.
  * 
  * @param string uid The 6-digit id of an image.
  * @param string file Path to the image file.
  * @param string type Specify if image is primary or thumb.
  */
 public function add($uid, $file, $type = 'primary') {
  $image = new \Imagick(ROOT_DIR . $file);
  $width = $this->width($image);
  $height = $this->height($image);
  $size = $this->size($image);
  $hash = $this->hash($image);
  $format = $this->format($image);

  $query = new \Peyote\Insert('media');
  $query->columns(['uid', 'file', 'width', 'height', 'size', 'hash', 'format', 'type'])
        ->values([$uid, $file, $width, $height, $size, $hash, $format, $type]);
  $this->db->fetch($query);//need to verify this was successful
 }
 
 private function width(\Imagick $image) {
  $image = $image->coalesceImages();
  return $image->getImageWidth();
 }
 
 private function height(\Imagick $image) {
  $image = $image->coalesceImages();
  return $image->getImageHeight();
 }
 
 private function size(\Imagick $image) {
  return $image->getImageLength();
 }
 
 private function hash(\Imagick $image) {
  return md5($image);
 }
 
 private function format(\Imagick $image) {
  return strtolower($image->getImageFormat());
 }
 
 /**
  * Get media
  *
  * Retrieve media associated with an image.
  * 
  * @param string uid The 6-digit id of an image.
  */
 
 public function get($uid) {
  $query = new \Peyote\Select('media');
  $query->where('uid', '=', $uid);
  $results = $this->db->fetch($query);
  $formatter = new \Rych\ByteSize\Formatter\Binary;
  $bytesize = new \Rych\ByteSize\ByteSize($formatter);
  foreach ($results as $m) {
   $result[$m['type']] = $m;
   $siteURL = $this->siteURL();
   $result[$m['type']]['url'] = $siteURL['scheme'] .'://' . $m['type'] . '.' . $siteURL['host'] . $result[$m['type']]['file'];
   $result[$m['type']]['readable_size'] = $bytesize->format($result[$m['type']]['size']);
  }
  return $result;
 }
 
}
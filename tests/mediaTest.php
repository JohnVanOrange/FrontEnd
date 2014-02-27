<?php
require_once('settings.inc');

class mediaTest extends PHPUnit_Framework_TestCase {
 
 protected $media;
 protected $uid;
 protected $helper;
 
 protected function setUp(){
  $this->media = new JohnVanOrange\jvo\Media;
  $this->helper = new Helper;
  $this->uid = $this->helper->UID(6);
 }
 protected function tearDown(){
  $db = $this->helper->db();
  $query = new \Peyote\Delete('media');
  $query->where('file', '=', '/media/orange_slice16.png');
  $db->fetch($query);
  unset($this->media);
 }

 public function test_add() {
  $this->media->add($this->uid, '/media/orange_slice16.png');
  $result = $this->media->get($this->uid);
  $this->assertEquals($result[0]['width'], '16', 'Width not correct');
  $this->assertEquals($result[0]['height'], '16', 'Height not correct');
  $this->assertEquals($result[0]['hash'], '26e46ffc3d711d7b801d1eb78f3cc1ff', 'Hash not correct');
  $this->assertEquals($result[0]['size'], '3632', 'Size not correct');
  $this->assertEquals($result[0]['format'], 'png', 'Format not correct');
  $this->assertEquals($result[0]['file'], '/media/orange_slice16.png', 'File name not correct');
  $this->assertEquals($result[0]['type'], 'primary', 'Image type not correct');
 }
 public function test_add_fail() {

 }

}

class Helper extends JohnVanOrange\jvo\Base {
  public function db() {
    return $this->db;
  }
  
  public function UID($length) {
    return $this->generateUID($length);
  }
}
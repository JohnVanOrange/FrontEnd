<?php
//Requires PHPUnit
require_once('../settings.inc');
require_once(ROOT_DIR.'/classes/base.class.php');

class baseTest extends PHPUnit_Framework_TestCase {
 
 protected $base;
 protected function setUp(){
  $this->base = new Base();
 }
 protected function tearDown(){
  unset($this->base);
 }
 
 /**** text2slug ****/
 public function test_text2slug() {
  $result = $this->base->text2slug('This is a test');
  $this->assertEquals('this-is-a-test',$result);
  $result = $this->base->text2slug('text');
  $this->assertEquals('text',$result);
 }
 
}
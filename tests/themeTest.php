<?php
//Requires PHPUnit
require_once('../vendor/autoload.php');
require_once('../settings.inc');

class themeTest extends PHPUnit_Framework_TestCase {
 
 protected $theme;
 protected $sid;
 protected function setUp(){
  $this->theme = new JohnVanOrange\jvo\Theme();
  $user = new JohnVanOrange\jvo\User();
  $login = $user->login('testuser', 'testpass');
  $this->sid = $login['sid'];
 }
 protected function tearDown(){
  unset($this->theme);
 }
 
 /**** set ****/
 //Not currently testing any of the cookie based functionality
 public function test_set_loggedin_success() {
  $result = $this->theme->set('dark',$this->sid);
  $this->assertEquals($result['message'],'Theme updated');
 }
 public function test_set_loggedin_fail() {
  try {
   $result = $this->theme->set('not_valid',$this->sid);
  }
  catch (Exception $e) {
   return;
  }
  $this->fail('An expected exception has not been raised.');
 }
 
 /**** get ****/
 //Not currently testing any of the cookie based functionality
 public function test_get_loggedin() {
  $this->theme->set('dark',$this->sid);
  $result = $this->theme->get($this->sid);
  $this->assertEquals('dark',$result);
  $this->theme->set('light',$this->sid);
  $result = $this->theme->get($this->sid);
  $this->assertEquals('light',$result);
 }
 
}
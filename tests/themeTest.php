<?php
//Requires PHPUnit
require_once('../settings.inc');
require_once(ROOT_DIR.'/classes/theme.class.php');
require_once(ROOT_DIR.'/classes/user.class.php');

class themeTest extends PHPUnit_Framework_TestCase {
 
 protected $theme;
 protected $sid;
 protected function setUp(){
  $this->theme = new Theme();
  $user = new User();
  $login = $user->login(array(
   'username' => 'testuser',
   'password' => 'testpass'
  ));
  $this->sid = $login['sid'];
 }
 protected function tearDown(){
  unset($this->theme);
 }
 
 /**** set ****/
 //Not currently testing any of the cookie based functionality
 public function test_set_loggedin_success() {
  $result = $this->theme->set(array('theme'=>'dark','sid'=>$this->sid));
  $this->assertEquals('Theme updated.',$result['message']);
 }
 public function test_set_loggedin_fail() {
  try {
   $result = $this->theme->set(array('theme'=>'not_valid','sid'=>$this->sid));
  }
  catch (Exception $e) {
   return;
  }
  $this->fail('An expected exception has not been raised.');
 }
 
 /**** get ****/
 //Not currently testing any of the cookie based functionality
 public function test_get_loggedin() {
  $this->theme->set(array('theme'=>'dark','sid'=>$this->sid));
  $result = $this->theme->get(array('sid'=>$this->sid));
  $this->assertEquals('dark',$result);
  $this->theme->set(array('theme'=>'light','sid'=>$this->sid));
  $result = $this->theme->get(array('sid'=>$this->sid));
  $this->assertEquals('light',$result);
 }
 
}
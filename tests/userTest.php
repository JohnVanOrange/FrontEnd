<?php
//Requires PHPUnit
require_once('../vendor/autoload.php');
require_once('../settings.inc');

class userTest extends PHPUnit_Framework_TestCase {
 
 protected $user;
 protected function setUp(){
  $this->user = new JohnVanOrange\jvo\User();
 }
 protected function tearDown(){
  unset($this->user);
 }
 
 /**** isAdmin ****/
 public function test_is_admin_true() {
  $sid = $this->user->login('adminuser', 'testpass')['sid'];
  $isAdmin = $this->user->isAdmin($sid);
  $this->assertTrue($isAdmin, 'User is not admin');
 }
 public function test_is_admin_false() {
  $sid = $this->user->login('testuser', 'testpass')['sid'];
  $isAdmin = $this->user->isAdmin($sid);
  $this->assertFalse($isAdmin, 'User is shouldn\'t be admin');
 }
 
 /**** get ****/
 /* need to get by id as well, but that's less important */
 public function test_get_user_by_username() {
  $user = $this->user->get('testuser','username');
  $this->assertEquals($user['email_hash'], '55502f40dc8b7c769880b10874abc9d0', 'Email hash was not returned as expected.');
 }
 
 /**** current ****/
 /**** login ****/
 /**** logout ****/
 /**** add ****/
 /**** saved ****/
 /**** uploaded ****/
 
}
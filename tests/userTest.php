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
 public function test_current_loggedin() {
  $sid = $this->user->login('testuser', 'testpass')['sid'];
  $current = $this->user->current($sid);
  $this->assertEquals($current['username'], 'testuser', 'Username unexpected');
 }
 public function test_current_nouser() {
  $current = $this->user->current();
  $this->assertArrayNotHasKey('username', $current, 'Username shouldn\'t be present');
 }
 
 /**** login ****/
 public function test_login_success() {
  $sid = $this->user->login('testuser', 'testpass')['sid'];
  $this->assertEquals(strlen($sid), 16, 'Invalid sid');
 }
 public function test_login_fail() {
  try {
   $response = $this->user->login('testuser', 'badpass');
  }
  catch (Exception $e) {
   return;
  }
  $this->fail('An expected exception has not been raised.');
 }
 
 /**** logout ****/
 public function test_logout() {
  $sid = $this->user->login('testuser', 'testpass')['sid'];
  $current = $this->user->current($sid);
  $this->assertArrayHasKey('username', $current, 'Unable to verify login worked');
  $this->user->logout($sid);
  $current = $this->user->current($sid);
  $this->assertArrayNotHasKey('username', $current, 'Logout failed');
 }
 
 /**** add ****/
 /**** saved ****/
 /**** uploaded ****/
 
}
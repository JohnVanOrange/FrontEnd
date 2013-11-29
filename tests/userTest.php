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
  $this->assertFalse($current, 'Current user should return false');
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
  $this->assertFalse($current, 'Current user should return false');
 }
 
 /**** add ****/
 public function test_add_success() {
  $uid = new Uid;
  $uid = $uid->generate(10);
  $create = $this->user->add('test_'.$uid, 'testpass_'.$uid, 'test@example.com');
  $this->assertEquals($create['message'], 'User added.');
 }
 public function test_add_no_username() {
  try {
   $uid = new Uid;
   $uid = $uid->generate(10);
   $this->user->add(NULL, 'testpass_'.$uid, 'test@example.com');
  }
  catch (Exception $e) {
   return;
  }
  $this->fail('An expected exception has not been raised.');
 }
 public function test_add_no_password() {
  try {
   $uid = new Uid;
   $uid = $uid->generate(10);
   $this->user->add('test_'.$uid, NULL, 'test@example.com');
  }
  catch (Exception $e) {
   return;
  }
  $this->fail('An expected exception has not been raised.');
 }
 public function test_add_no_email() {
  try {
   $uid = new Uid;
   $uid = $uid->generate(10);
   $this->user->add('test_'.$uid, 'testpass_'.$uid);
  }
  catch (Exception $e) {
   return;
  }
  $this->fail('An expected exception has not been raised.');
 }
 
 /**** saved ****/
 public function test_saved_loggedin() {
  $sid = $this->user->login('testuser', 'testpass')['sid'];
  $results = $this->user->saved(NULL, $sid);
  //TODO: figure out how to assert results here, but there should at least not be an error
 }
 public function test_saved_loggedin_otheruser() {
  try {
   $sid = $this->user->login('testuser', 'testpass')['sid'];
   $results = $this->user->saved('adminuser', $sid);
  }
  catch (Exception $e) {
   return;
  }
  $this->fail('An expected exception has not been raised.');
 }
 public function test_saved_loggedout() {
  try {
   $results = $this->user->saved('adminuser');
  }
  catch (Exception $e) {
   return;
  }
  $this->fail('An expected exception has not been raised.');
 }
 
 /**** uploaded ****/
 public function test_uploaded_loggedin() {
  $sid = $this->user->login('testuser', 'testpass')['sid'];
  $results = $this->user->uploaded(NULL, $sid);
  //TODO: figure out how to assert results here, but there should at least not be an error
 }
 public function test_uploaded_loggedin_otheruser() {
  try {
   $sid = $this->user->login('testuser', 'testpass')['sid'];
   $results = $this->user->uploaded('adminuser', $sid);
  }
  catch (Exception $e) {
   return;
  }
  $this->fail('An expected exception has not been raised.');
 }
 public function test_uploaded_loggedout() {
  try {
   $results = $this->user->uploaded('adminuser');
  }
  catch (Exception $e) {
   return;
  }
  $this->fail('An expected exception has not been raised.');
 }
 
}

class Uid extends JohnVanOrange\jvo\Base{
 public function generate($length = 10) {
  return $this->generateUID($length);
 }
}

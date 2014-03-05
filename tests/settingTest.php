<?php
require_once('settings.inc');

class settingTest extends PHPUnit_Framework_TestCase {
 
 protected $setting;
 protected $user;
 
 protected function setUp(){
  $this->setting = new JohnVanOrange\jvo\Setting;
  $this->user = new JohnVanOrange\jvo\User;
 }
 protected function tearDown(){
  unset($this->setting);
  unset($this->user);
 }

 public function test_get() {
  $result = $this->setting->get('theme');
  $this->assertEquals($result['value'], 'jvo', 'Incorrect theme setting result');

 }
 public function test_set() {
  $sid = $this->user->login('adminuser', 'testpass')['sid'];
  $this->setting->update('branch', 'test', $sid);
  $result = $this->setting->get('branch');
  $this->assertEquals($result['value'], 'test', 'Incorrect initial branch setting');
  $this->setting->update('branch', 'master', $sid);
  $result = $this->setting->get('branch');
  $this->assertEquals($result['value'], 'master', 'Incorrect secondary branch setting');
 }
 
 public function test_set_notadmin() {
  $sid = $this->user->login('testuser', 'testpass')['sid'];
  try {
    $this->setting->update('branch', 'test', $sid);
  }
  catch (Exception $e) {
   return;
  }
  $this->fail('An expected exception has not been raised.');
 }

}
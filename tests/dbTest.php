<?php
//Requires PHPUnit
require_once('../vendor/autoload.php');
require_once('../settings.inc');

use JohnVanOrange\jvo;
class dbTest extends PHPUnit_Framework_TestCase {
 
 protected $db;
 protected function setUp(){
  $this->db = new JohnVanOrange\jvo\DB('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
 }
 protected function tearDown(){
  unset($this->db);
 }
 
 /**** fetch ****/
 public function test_fetch_success() {
  $query = new \Peyote\Select('report_types');
  $result = $this->db->fetch($query);
  $this->assertInternalType('array',$result);
  $query = "SELECT * FROM report_types WHERE value = :value";
  $val = array(
   ':value' => 'NSFW'
  );
  $query = new \Peyote\Select('report_types');
  $query->where('value', '=', 'NSFW');
  $result = $this->db->fetch($query);
  $this->assertInternalType('array',$result);
 }
 
}
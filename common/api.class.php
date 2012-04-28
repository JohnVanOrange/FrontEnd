<?php
//require_once('../settings.inc');
require(ROOT_DIR.'/common/db.class.php');

class API {

 protected $db;

 public function __construct($options=array()) {
  $this->db = new DB('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
 }

 public function addImage($options=array()) {
  return NULL;
 }

 public function reportImage($options=array()) {
  return NULL;
 }

 public function randomImage($options=array()) {
  $sql = 'SELECT * FROM images WHERE display = "1" ORDER BY RAND() LIMIT 1';
  $result = $this->db->fetch($sql);
  return $result[0];
 }

 public function getImage($options=array()) {
  //need to add error handling when the images are set to display = 0
  $sql = 'SELECT * from images WHERE filename = :filename LIMIT 1;';
  $val = array(
   ':filename' => $options['image']
  );
  $result = $this->db->fetch($sql,$val);
  return $result[0];
 }

}
?>

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
  $sql = 'INSERT INTO reports';
  return NULL;
 }

 public function randomImage($options=array()) {
  $sql = 'SELECT filename FROM images WHERE display = "1" ORDER BY RAND() LIMIT 1';
  $result = $this->db->fetch($sql);
  return $result[0]['filename'];
 }

 public function getImage($options=array()) {
  $sql = 'SELECT * from images WHERE filename = :filename LIMIT 1;';
  $val = array(
   ':filename' => $options['image']
  );
  $result = $this->db->fetch($sql,$val);
  if (!$result) throw new Exception('Image not found', 404);
  $result = $result[0];
  if (!$result['display']) {
   unset($result['filename']);
   unset($result['hash']);
   unset($result['height']);
   unset($result['width']);
   unset($result['type']);
  }
  return $result;
 }

}
?>

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
  //Add report
  $sql = 'INSERT INTO reports(image_id, report_type, reason) VALUES(:image_id, :report_type, :reason)';
  $val = array(
   ':image_id' => $options['id'],
   ':report_type' => $options['type'],
   ':reason' => $options['reason']
  );
  $this->db->fetch($sql,$val);
  //Hide image
  $sql = 'UPDATE images SET display=0 WHERE id = :image_id';
  $val = array (
   ':image_id' => $options['id']
  );
  return $this->db->fetch($sql,$val);
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
  if (!$result['display']) throw new Exception('Image removed', 403); 
  return $result;
 }

}
?>

<?php
require(ROOT_DIR.'/common/db.class.php');

class API {

 protected $db;

 public function __construct($options=array()) {
  $this->db = new DB('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
 }

 private function addImage($options=array()) {
  $info = getimagesize($options['path']);
  if (!$info) {
   unlink($options['path']);
   throw new Exception('Not a valid image');
  }
  $filetypepart = explode('/',$info['mime']);
  $type = end($filetypepart);
  $width = $info[0];
  $height = $info[1];
  $hash = md5_file($options['path']);
  $fullfilename = $options['path'].'.'.$type;
  rename($options['path'],$fullfilename);
  $filenamepart = explode('/',$fullfilename);
  $filename = end($filenamepart);
  $namepart = explode('.',$filename);
  $name = $namepart[0];
  //need to add duplicate checking eventually
  $sql = "INSERT INTO images(name, filename, hash, type, width, height) VALUES(:name, :filename, :hash, :type, :width, :height)";
  $val = array(
   ':name' => $name,
   ':filename' => $filename,
   ':hash' => $hash,
   ':type' => $type,
   ':width' => $width,
   ':height' => $height,
  );
  $s = $this->db->prepare($sql);
  $s->execute($val);
  return array(
   'page' => WEB_ROOT.'display/'.$filename,
   'image' => WEB_ROOT.'media/'.$filename,
   'message' => 'Image added.'
  );
 }
 
 public function addImagefromUpload($options=array()) {
  $filename = md5(mt_rand().$options['path']);
  $newpath = ROOT_DIR.'/media/'.$filename;
  rename($options['path'],$newpath);
  return $this->addImage(array('path'=>$newpath));
 }

 public function addImagefromURL($options=array()) {
  $image = $this->remoteFetch(array('url'=>$options['url']));
  $filename = md5(mt_rand().$options['url']);
  $newpath = ROOT_DIR.'/media/'.$filename;
  file_put_contents($newpath,$image);
  return $this->addImage(array('path'=>$newpath));
 }

  private function remoteFetch($options=array()) {
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $options['url']);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 File Retrieval Bot by /u/cbulock (+'.WEB_ROOT.'bot)');
   curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
   curl_setopt($ch, CURLOPT_TIMEOUT, 20);
   $response = curl_exec($ch);
   curl_close($ch);
   return $response;
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
  $this->db->fetch($sql,$val);
  return array(
   'message' => 'Image Reported.'
  );
 }

 public function randomImage($options=array()) {
  $sql = 'SELECT name FROM images WHERE display = "1" ORDER BY RAND() LIMIT 1';
  $result = $this->db->fetch($sql);
  return $result[0]['name'];
 }

 public function getImage($options=array()) {
  if (!$options['image']) throw new Exception('No image given.');
  if (strpos($options['image'],'.') === FALSE) {
   $sql = 'SELECT * from images WHERE name = :name LIMIT 1;';
  }
  else {
   $sql = 'SELECT * from images WHERE filename = :name LIMIT 1;';
  }
  $val = array(
   ':name' => $options['image']
  );
  $result = $this->db->fetch($sql,$val);
  if (!$result) throw new Exception('Image not found', 404);
  $result = $result[0];
  if (!$result['display']) throw new Exception('Image removed', 403); 
  return $result;
 }

}
?>

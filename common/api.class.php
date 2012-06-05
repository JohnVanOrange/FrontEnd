<?php
require(ROOT_DIR.'/common/db.class.php');

class API {

 protected $db;

 public function __construct($options=array()) {
  $this->db = new DB('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
 }

 private function addImage($options=array()) {
  if (!isset($options['c_link'])) $options['c_link'] = NULL;
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
  $dupsql = "SELECT * FROM images WHERE hash ='".$hash."' LIMIT 1";//checking for dups
  $uid = $this->getUID();
  $result = $this->db->fetch($dupsql);
  if ($result) {
   unlink($fullfilename);
   return array(
    'page' => WEB_ROOT.'v/'.$result[0]['uid'],
    'image' => WEB_ROOT.'media/'.$result[0]['filename'],
    'message' => 'Duplicate image.'
   );
  }
  else {
   $sql = "INSERT INTO images(name, filename, uid, hash, type, width, height, c_link) VALUES(:name, :filename, :uid, :hash, :type, :width, :height, :c_link)";
   $val = array(
    ':name' => $name,
    ':filename' => $filename,
    ':uid' => $uid,
    ':hash' => $hash,
    ':type' => $type,
    ':width' => $width,
    ':height' => $height,
    ':c_link' => $options['c_link']
   );
   $s = $this->db->prepare($sql);
   $s->execute($val);//need to verify this was successful
   return array(
    'page' => WEB_ROOT.'v/'.$uid,
    'image' => WEB_ROOT.'media/'.$filename,
    'message' => 'Image added.'
   );
  }
 }
 
 public function addImagefromUpload($options=array()) {
  $filename = md5(mt_rand().$options['path']);
  $newpath = ROOT_DIR.'/media/'.$filename;
  rename($options['path'],$newpath);
  return $this->addImage(array('path'=>$newpath));
 }

 public function addImagefromURL($options=array()) {
  if (!$options['url']) throw new Exception('Missing URL',1000);
  $image = $this->remoteFetch(array('url'=>$options['url']));
  $filename = md5(mt_rand().$options['url']);
  $newpath = ROOT_DIR.'/media/'.$filename;
  file_put_contents($newpath,$image);
  return $this->addImage(array('path'=>$newpath,'c_link'=>$options['c_link']));
 }

  protected function remoteFetch($options=array()) {
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
  $sql = 'SELECT uid FROM images WHERE display = "1" ORDER BY RAND() LIMIT 1';
  $result = $this->db->fetch($sql);
  return $result[0]['uid'];
  //this should return image URL's as well
 }

 public function getImage($options=array()) {
  if (!$options['image']) throw new Exception('No image given.');
  switch (strlen($options['image'])) {
   case 6:
    $sql = 'SELECT * from images WHERE uid = :name LIMIT 1;';
   break;
   case 32:
    $sql = 'SELECT * from images WHERE name = :name LIMIT 1;';
   break;
   default:
    $sql = 'SELECT * from images WHERE filename = :name LIMIT 1;';
   break;
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

 public function getUID($options = array()) {
  if (!isset($options['length'])) $options['length'] = 6;
  do {
   $uid = $this->generateUID($options['length']);
   $sql = 'SELECT uid FROM images WHERE uid = "'.$uid.'" LIMIT 1';
   $not_unique = $this->db->fetch($sql);
  } while (count($not_unique));
  return $uid;
 }

 private function generateUID($length) {
  $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  $uid = '';
  for($i=0;$i<$length;$i++) {
   $uid .= $chars[rand(0,strlen($chars)-1)];
  }
  return $uid;
 }

}
?>

<?php
require_once(ROOT_DIR.'/classes/base.class.php');
require_once(ROOT_DIR.'/classes/tag.class.php');
require_once(ROOT_DIR.'/classes/user.class.php');

class Image extends Base {

 private $tag;
 private $user;

 public function __construct($options=array()) {
  parent::__construct();
  $this->tag = new Tag;
  $this->user = new User;
 }

 private function add($options=array()) {
  if (!isset($options['c_link'])) $options['c_link'] = NULL;
  $info = getimagesize($options['path']);
  if (!$info) {
   unlink($options['path']);
   throw new Exception('Not a valid image',1100);
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
  $dupsql = "SELECT * FROM images WHERE hash ='".$hash."' LIMIT 1";
  $uid = $this->getUID();
  $result = $this->db->fetch($dupsql);
  if ($result) {
   unlink($fullfilename);
   return array(
    'url' => WEB_ROOT.'v/'.$result[0]['uid'],
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
    'url' => WEB_ROOT.'v/'.$uid,
    'image' => WEB_ROOT.'media/'.$filename,
    'message' => 'Image added.'
   );
  }
 }
 
 public function addFromUpload($options=array()) {
  $filename = md5(mt_rand().$options['path']);
  $newpath = ROOT_DIR.'/media/'.$filename;
  rename($options['path'],$newpath);
  return $this->add(array('path'=>$newpath));
 }

 public function addFromURL($options=array()) {
  if (!$options['url']) throw new Exception('Missing URL',1000);
  $image = $this->remoteFetch(array('url'=>$options['url']));
  $filename = md5(mt_rand().$options['url']);
  $newpath = ROOT_DIR.'/media/'.$filename;
  file_put_contents($newpath,$image);
  return $this->add(array('path'=>$newpath,'c_link'=>$options['c_link']));
 }

 public function report($options=array()) {
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

 public function random($options=array()) {
  if ($options['tag']) {
   $sql = 'SELECT id FROM tag_list WHERE basename = :basename';
   $val = array(
    ':basename' => $options['tag']
   );
   $result = $this->db->fetch($sql,$val);
   $sql = 'SELECT image_id FROM tags WHERE tag_id ='.$result[0]['id'].' ORDER BY RAND() LIMIT 1';
   $result = $this->db->fetch($sql);
   $sql = 'SELECT uid FROM images WHERE id ='.$result[0]['image_id'];
  }
  else {
   $sql = 'SELECT uid FROM images WHERE display = "1" ORDER BY RAND() LIMIT 1';
  }
  $result = $this->db->fetch($sql);
  if (!$result) throw new Exception('No image results', 404);
  return $result[0]['uid'];
  //this should return image URL's as well
 }

 public function get($options=array()) {
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
  $result['tags'] = $this->tag->get(array('value'=>$result['id']));
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

}
?>

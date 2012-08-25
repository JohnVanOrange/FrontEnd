<?php
require_once(ROOT_DIR.'/classes/base.class.php');
require_once(ROOT_DIR.'/classes/tag.class.php');
require_once(ROOT_DIR.'/classes/user.class.php');
require_once(ROOT_DIR.'/classes/report.class.php');

class Image extends Base {

 private $tag;
 private $user;
 private $report;

 public function __construct($options=array()) {
  parent::__construct();
  $this->tag = new Tag;
  $this->user = new User;
  $this->report = new Report;
 }
 
 public function save($options=array()) {
  $current = $this->user->current($options);
  $ip = $_SERVER['REMOTE_ADDR'];
  if (!$current) throw new Exception('Must be logged in to save images',1020);
  $sql = 'INSERT INTO resources(ip, image, user_id, type) VALUES(:ip, :image, :user_id, "save")';
  $val = array(
   ':ip' => $ip,
   ':image' => $options['image'],
   ':user_id' => $current['id']
  );
  $this->db->fetch($sql,$val);
  return array(
   'message' => 'Image saved.'
  );
 }

 public function saved($options=array()) {
  $current = $this->user->current($options);
  if (!$current) throw new Exception('Must be logged in to view saved images');
  $sql = 'SELECT image FROM resources WHERE user_id = '.$current['id'].' AND type = "save"';
  $results = $this->db->fetch($sql);
  foreach ($results as $result) {
   try {
    $return[] = $this->get(array('image'=>$result['image']));
   }
   catch(Exception $e) {
    if ($e->getCode() != 403) {
     throw new Exception($e);
    }
   }
  }
  return $return;
 }

 public function unsave($options=array()) {
  $current = $this->user->current($options);
  if (!$current) throw new Exception('Must be logged in to unsave images',1021);
  $sql = 'DELETE FROM resources WHERE (image = :image AND user_id = :user_id AND type = "save")';
  $val = array(
   ':image' => $options['image'],
   ':user_id' => $current['id']
  );
  $this->db->fetch($sql,$val);
  return array(
   'message' => 'Image unsaved.'
  );
 }
 
 public function approve($options=array()) {
  $current = $this->user->current($options);
  if ($current['type'] < 2) throw new Exception('Must be an admin to access method', 401);
  $sql = 'UPDATE reports SET resolved = 1 WHERE image_id = :id';
  $val = array(
   ':id' => $options['id']
  );
  $this->db->fetch($sql,$val);
  $sql = 'UPDATE images SET approved = 1, display = 1 WHERE id = :id';
  $this->db->fetch($sql,$val);
  return array(
   'message' => 'Image approved.'
  );
 }
 
 public function remove($options=array()) {
  $current = $this->user->current($options);
  if ($current['type'] < 2) throw new Exception('Must be an admin to access method', 401);
  if (!$options['uid']) {
   if (!$options['id']) throw new Exception('ID or UID required');
   $sql = 'SELECT uid FROM images WHERE id = :id';
   $val = array(
    ':id' => $options['id']
   );
   $result = $this->db->fetch($sql,$val);
   $options['uid'] = $result[0]['uid'];
  }
  if (!$options['id']) {
   $sql = 'SELECT id FROM images WHERE uid = :uid';
   $val = array(
    ':uid' => $options['uid']
   );
   $result = $this->db->fetch($sql,$val);
   $options['id'] = $result[0]['id'];
  }
  $sql = 'SELECT filename FROM images WHERE uid = :uid';
  $val = array(
   ':uid' => $options['uid']
  );
  $result = $this->db->fetch($sql,$val);
  $filename = $result[0]['filename'];
  //clean up resources
  $sql = 'DELETE FROM resources WHERE image = :uid';
  $this->db->fetch($sql,$val);
  //remove image in db
  $sql = 'DELETE FROM images WHERE uid = :uid';
  $this->db->fetch($sql,$val);
  //clean up reports
  $val = array(
   ':id' => $options['id']
  );
  $sql = 'DELETE FROM reports WHERE image_id = :id';
  $this->db->fetch($sql,$val);
  //clean up tags
  $sql = 'DELETE FROM tags WHERE image_id = :id';
  $this->db->fetch($sql,$val);
  //remove image
  unlink(ROOT_DIR.'/media/'.$filename);
  unlink(ROOT_DIR.'/media/thumbs/'.$filename);
  return array(
   'message' => 'Image removed.'
  );
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
    'thumb' => WEB_ROOT.'media/thumbs/'.$result[0]['filename'],
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
   $thumb = $this->scale(array('image'=>$uid));
   file_put_contents(ROOT_DIR.'/media/thumbs/'.$filename,$thumb);
   return array(
    'url' => WEB_ROOT.'v/'.$uid,
    'image' => WEB_ROOT.'media/'.$filename,
    'thumb' => WEB_ROOT.'media/thumbs/'.$filename,
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
 
 public function reported($options=array()) {
  $current = $this->user->current($options);
  if ($current['type'] < 2) throw new Exception('Must be an admin to access method', 401); 
  $sql = 'SELECT * FROM reports WHERE resolved = 0 ORDER BY RAND() LIMIT 1';
  $report_result = $this->db->fetch($sql);
  $report_type = $this->report->get(array('id'=>$report_result[0]['report_type']));
  $report_result[0]['value'] = $report_type[0]['value'];
  $sql = 'SELECT * FROM images WHERE id = '.$report_result[0]['image_id'];
  $image_result = $this->db->fetch($sql);
  if (!$image_result) throw new Exception('No image result', 404);
  return array(
   'report' => $report_result[0],
   'image' => $image_result[0]
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
  $image = $this->get(array('image'=>$result[0]['uid']));
  $image['response'] = $image['uid']; //backwards compatibility
  return $image;
 }

 public function get($options=array()) {
  if (!$options['image']) throw new Exception('No image given.', 404);
  #Get image data
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
  #See if there was a result
  if (!$result) throw new Exception('Image not found', 404);
  $result = $result[0];
  #Verify image isn't supposed to be hidden
  if (!$result['display']) throw new Exception('Image removed', 403);
  #Get tags
  $result['tags'] = $this->tag->get(array('value'=>$result['id']));
  #Get resources
  $user = $this->user->current($options);
  if ($user) {
   $sql = 'SELECT * FROM resources WHERE (image = "'.$result['uid'].'" AND user_id = "'.$user['id'].'")';
   $resources = $this->db->fetch($sql);
   foreach ($resources as $r) {
    $data[$r['type']] = $r;
   }
   $result['data'] = $data;
  }
  return $result;
 //this should return image URL's as well
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
 
 public function scale($options=array()) {
  if (!$options['width']) $options['width'] = '240';
  if (!$options['height']) $options['height'] = '160';
  $imagedata = $this->get(array('image'=>$options['image']));
  $image = new Imagick(ROOT_DIR.'/media/'.$imagedata['filename']);

  foreach ($image as $frame) {
   $frame->thumbnailImage($options['width'],$options['height'],TRUE);
  }
  //header('Content-type: '.$image->getImageMimeType());
  return $image->getImagesBlob(); 
 }

}
?>

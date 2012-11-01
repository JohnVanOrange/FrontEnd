<?php
require_once(ROOT_DIR.'/classes/base.class.php');
require_once(ROOT_DIR.'/classes/tag.class.php');
require_once(ROOT_DIR.'/classes/user.class.php');
require_once(ROOT_DIR.'/classes/report.class.php');
require_once(ROOT_DIR.'/classes/resource.class.php');

class Image extends Base {

 private $user;
 private $res;

 public function __construct($options=array()) {
  parent::__construct();
  $this->user = new User;
  $this->res = new Resource;
 }
 
 public function save($options=array()) {
  $current = $this->user->current($options);
  if (!$current) throw new Exception('Must be logged in to save images',1020);
  $res = array(
   'image' => $options['image'],
   'type' => 'save'
  );
  $this->res->add($res);
  return array(
   'message' => 'Image saved.',
   'saved' => 1
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
   'message' => 'Image unsaved.',
   'saved' => 0
  );
 }
 
 public function approve($options=array()) {
  $current = $this->user->current($options);
  if ($current['type'] < 2) throw new Exception('Must be an admin to access method', 401);
  if (!$options['image']) throw new Exception('Image UID required');
  $nsfw = 0;
  if ($options['nsfw']) $nsfw = 1;
  $sql = 'DELETE FROM resources WHERE image = :image AND type = "report";';
  $val = array(
   ':image' => $options['image']
  );
  $this->db->fetch($sql,$val);
  $sql = 'UPDATE images SET display = 1, approved = 1, nsfw = '. $nsfw.' WHERE uid = :image';
  $this->db->fetch($sql,$val);
  return array(
   'message' => 'Image approved.'
  );
 }
 
 public function remove($options=array()) {
  $current = $this->user->current($options);
  if ($current['type'] < 2) throw new Exception('Must be an admin to access method', 401);
  if (!$options['image']) throw new Exception('Image UID required');
  $sql = 'SELECT filename FROM images WHERE uid = :image';
  $val = array(
   ':image' => $options['image']
  );
  $result = $this->db->fetch($sql,$val);
  $filename = $result[0]['filename'];
  //clean up resources
  $sql = 'DELETE FROM resources WHERE image = :image';
  $this->db->fetch($sql,$val);
  //remove image in db
  $sql = 'DELETE FROM images WHERE uid = :image';
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
   $res = array(
    'image' => $uid,
	'type' => 'upload'
   );
   $this->res->add($res);
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
  $res = array(
   'image' => $options['image'],
   'value' => $options['type'],
   'type' => 'report'
  );
  $this->res->add($res);
  //Hide image
  $sql = 'UPDATE images SET display=0 WHERE uid = :uid';
  $val = array (
   ':uid' => $options['image']
  );
  $this->db->fetch($sql,$val);
  $message = 'A new image was reported on '. SITE_NAME . ".\n\n";
  $message .= "View Reported Image:\n";
  $message .= WEB_ROOT.'admin/image/'.$options['image']."\n\n";
  $message .= "IP:\n";
  $message .= $_SERVER['REMOTE_ADDR'];
  mail(
   ADMIN_EMAIL,
   'New Reported Image for '. SITE_NAME,
   $message
  );
  return array(
   'message' => 'Image Reported.'
  );
 }
 
 public function reported($options=array()) {
  $current = $this->user->current($options);
  if ($current['type'] < 2) throw new Exception('Must be an admin to access method', 401); 
  $sql = 'SELECT * FROM resources WHERE type = "report" ORDER BY RAND() LIMIT 1;';
  $report_result = $this->db->fetch($sql);
  $image_result = $this->get(array('image'=>$report_result[0]['image']));
  if (!$image_result) throw new Exception('No image result', 404);
  return $image_result;
 }
 
 public function unapproved($options=array()) {
  $current = $this->user->current($options);
  if ($current['type'] < 2) throw new Exception('Must be an admin to access method', 401); 
  $sql = 'SELECT uid FROM images WHERE approved = 0 ORDER BY RAND() LIMIT 1;';
  $image = $this->db->fetch($sql);
  $image_result = $this->get(array('image'=>$image[0]['uid']));
  if (!$image_result) throw new Exception('No image result', 404);
  return $image_result;
 }

 public function random($options=array()) {
  if ($options['new']) {
   // TODO eventually make the value a user selectable amount
   $sql = 'SELECT uid FROM (SELECT uid, display FROM images ORDER BY id DESC LIMIT 500) AS new WHERE display = "1" ORDER BY RAND() LIMIT 1';
  }
  elseif ($options['tag']) {
   $sql = 'SELECT id FROM tag_list WHERE basename = :basename';
   $val = array(
    ':basename' => $options['tag']
   );
   $result = $this->db->fetch($sql,$val);
   //TODO this probably needs to make sure that it's only returning images that are allowed to be displayed
   $sql = 'SELECT image AS uid FROM resources WHERE (value = '.$result[0]['id'].' AND type = "tag") ORDER BY RAND() LIMIT 1';
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
  $tag = new Tag;
  $current = $this->user->current($options);
  if (!$options['image']) throw new Exception('No image given.', 404);
  #Get image data
  switch (strlen($options['image'])) {
   case 6:
    $sql = 'SELECT * from images WHERE uid = :name LIMIT 1;';
   break;
   default:
    $sql = 'SELECT * from images WHERE filename = :name LIMIT 1;';
   break;
  }
  $val = array(
   ':name' => $options['image']
  );
  $result = $this->db->fetch($sql,$val);
  //See if there was a result
  if (!$result) throw new Exception('Image not found', 404);
  $result = $result[0];
  //Verify image isn't supposed to be hidden
  if (!$result['display'] AND $current['type'] < 2) throw new Exception('Image removed', 403);
  //Get tags
  $tag_result = $tag->get(array('value'=>$result['uid']));
  if ($tag_result) $result['tags'] = $tag_result;
  //Get uploader
  $sql = 'SELECT * FROM resources WHERE (image = "'.$result['uid'].'" AND type = "upload" AND user_id IS NOT NULL)';
  $uploader = $this->db->fetch($sql);
  if ($uploader) {
   $result['uploader'] = $this->user->get($uploader[0]['user_id']);
  }
  //Get resources
  if ($current) {
   $sql = 'SELECT * FROM resources WHERE (image = "'.$result['uid'].'" AND user_id = "'.$current['id'].'")';
   $resources = $this->db->fetch($sql);
   foreach ($resources as $r) {
    $data[$r['type']] = $r;
   }
   $result['data'] = $data;
   if ($data['save']) $result['saved'] = 1;
  }
  //Page title
  $result['page_title'] = SITE_NAME . ' - ';
  if ($result['tags']) {
   $title_text = '';
   foreach ($result['tags'] as $tag) {
    $title_text .= $tag['name'] . ', ';
   }
   $result['page_title'] .= rtrim($title_text, ', ');
  }
  else {
   $result['page_title'] .= $result['uid'];
  }
  //URLs
  $result['image_url'] = WEB_ROOT . 'media/'. $result['filename'];
  $result['page_url'] = WEB_ROOT . 'v/' . $result['uid'];
  if ($current['type'] > 1) { //if admin
   //Get report data
   $sql = 'SELECT * FROM resources WHERE type = "report" AND image = "' . $result['uid'] . '" LIMIT 1;';
   $report_result = $this->db->fetch($sql);
   if ($report_result) {
    $report = new Report;
    $report_type = $report->get(array('id'=>$report_result[0]['value']));
    $report_result[0]['value'] = $report_type[0]['value'];
    $result['report'] = $report_result[0];
   }
  }
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
 
 function stats($options = array()) {
  $sql = 'SELECT (SELECT COUNT(*) from images) AS images,(SELECT COUNT(*) from resources WHERE type = "report") AS reports,(SELECT COUNT(*) from images WHERE approved = 1) AS approved';
  $result = $this->db->fetch($sql);
  return $result[0];
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

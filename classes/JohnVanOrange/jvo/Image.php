<?php
namespace JohnVanOrange\jvo;
use Imagick;

class Image extends Base {

 private $user;
 private $res;

 public function __construct() {
  parent::__construct();
  $this->user = new User;
  $this->res = new Resource;
 }
 
 /**
  * Like image
  *
  * Like an image. Must be logged in to use this method.
  *
  * @api
  * 
  * @param string $image The 6-digit id of an image.
  * @param string $sid Session ID that is provided when logged in. This is also set as a cookie. If sid cookie headers are sent, this value is not required.
  */
 
 public function like($image, $sid=NULL) {
  $current = $this->user->current($sid);
  if (!$current) throw new \Exception('Must be logged in to rate images',1022);
  $sql = 'DELETE FROM resources WHERE (image = :image AND user_id = :user_id AND type = "dislike")';
  $val = array(
   ':image' => $image,
   ':user_id' => $current['id']
  );
  $this->db->fetch($sql,$val);
  $this->res->add('like', $image);
  return array(
   'message' => 'Image liked.',
   'liked' => 1
  );
 }

 /**
  * Dislike image
  *
  * Dislike an image. Must be logged in to use this method.
  *
  * @api
  * 
  * @param string $image The 6-digit id of an image.
  * @param string $sid Session ID that is provided when logged in. This is also set as a cookie. If sid cookie headers are sent, this value is not required.
  */
 
 public function dislike($image, $sid=NULL) {
  $current = $this->user->current($sid);
  if (!$current) throw new \Exception('Must be logged in to rate images',1023);
  $sql = 'DELETE FROM resources WHERE (image = :image AND user_id = :user_id AND type = "like")';
  $val = array(
   ':image' => $image,
   ':user_id' => $current['id']
  );
  $this->db->fetch($sql,$val);
  $this->res->add('dislike', $image);
  return array(
   'message' => 'Image disliked.',
   'liked' => 0
  );
 } 
 
 /**
  * Save image
  *
  * Save an image for viewing later. Must be logged in to use this method.
  *
  * @api
  * 
  * @param string $image The 6-digit id of an image.
  * @param string $sid Session ID that is provided when logged in. This is also set as a cookie. If sid cookie headers are sent, this value is not required.
  */
 
 public function save($image, $sid=NULL) {
  $current = $this->user->current($sid);
  if (!$current) throw new \Exception('Must be logged in to save images',1020);
  $this->res->add('save', $image);
  return array(
   'message' => 'Image saved.',
   'saved' => 1
  );
 }

 /**
  * Unsave image
  *
  * Stop saving a previously saved image. Must be logged in to use this method.
  *
  * @api
  * 
  * @param string $image The 6-digit id of an image.
  * @param string $sid Session ID that is provided when logged in. This is also set as a cookie. If sid cookie headers are sent, this value is not required.
  */
 
 public function unsave($image, $sid=NULL) {
  $current = $this->user->current($sid);
  if (!$current) throw new \Exception('Must be logged in to unsave images',1021);
  $sql = 'DELETE FROM resources WHERE (image = :image AND user_id = :user_id AND type = "save")';
  $val = array(
   ':image' => $image,
   ':user_id' => $current['id']
  );
  $this->db->fetch($sql,$val);
  return array(
   'message' => 'Image unsaved.',
   'saved' => 0
  );
 }
 
 /**
  * Approve image
  *
  * Approves an image. If the image was reported, it will resolve all reports. If the image was hidden, it will now be displayed. Must be logged on as admin to access this method.
  *
  * @api
  * 
  * @param string $image The 6-digit id of an image.
  * @param string $sid Session ID that is provided when logged in. This is also set as a cookie. If sid cookie headers are sent, this value is not required.
  * @param bool $nsfw If an image should be marked as approved, but NSFW, setting this to 'true' or '1' will mark the image that way.
  */
 
 public function approve($image, $sid=NULL, $nsfw=NULL) {
  $current = $this->user->current($sid);
  if ($current['type'] < 2) throw new \Exception('Must be an admin to access method', 401);
  $nsfw = 0;
  if (isset($nsfw)) $nsfw = 1;
  $sql = 'DELETE FROM resources WHERE image = :image AND type = "report";';
  $val = array(
   ':image' => $image
  );
  $this->db->fetch($sql,$val);
  $sql = 'UPDATE images SET display = 1, approved = 1, nsfw = '. $nsfw.' WHERE uid = :image';
  $this->db->fetch($sql,$val);
  return array(
   'message' => 'Image approved.'
  );
 }
 
 /**
  * Remove image
  *
  * Removes an image and everything associated with it. Must be logged on as admin to access this method.
  *
  * @api
  * 
  * @param string $image The 6-digit id of an image.
  * @param string $sid Session ID that is provided when logged in. This is also set as a cookie. If sid cookie headers are sent, this value is not required.
  */
 
 public function remove($image, $sid=NULL) {
  $current = $this->user->current($sid);
  if ($current['type'] < 2) throw new \Exception('Must be an admin to access method', 401);
  $sql = 'SELECT filename FROM images WHERE uid = :image';
  $val = array(
   ':image' => $image
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

 private function add($path, $c_link=NULL) {
  $info = getimagesize($path);
  if (!$info) {
   unlink($path);
   throw new \Exception('Not a valid image',1100);
  }
  $filetypepart = explode('/',$info['mime']);
  $type = end($filetypepart);
  $width = $info[0];
  $height = $info[1];
  $hash = md5_file($path);
  $fullfilename = $path.'.'.$type;
  rename($path,$fullfilename);
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
    'url' => WEB_ROOT.$result[0]['uid'],
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
    ':c_link' => $c_link
   );
   $s = $this->db->prepare($sql);
   $s->execute($val);//need to verify this was successful
   $thumb = $this->scale($uid);
   file_put_contents(ROOT_DIR.'/media/thumbs/'.$filename,$thumb);
   $this->res->add('upload', $uid);
   return array(
    'url' => WEB_ROOT.$uid,
    'uid' => $uid,
    'image' => WEB_ROOT.'media/'.$filename,
    'thumb' => WEB_ROOT.'media/thumbs/'.$filename,
    'message' => 'Image added.'
   );
  }
 }
 
 public function addFromUpload($path) {
  $filename = md5(mt_rand().$path);
  $newpath = ROOT_DIR.'/media/'.$filename;
  rename($path,$newpath);
  return $this->add($newpath);
 }

 /**
  * Add image from URL
  *
  * Allows adding images to site from remote URL's.
  *
  * @api
  * 
  * @param string $url Full URL to an image to be added to the site. Must be JPEG, PNG, or GIF format.
  * @param string $c_link An optional external link to comments for the image.
  */
 
 public function addFromURL($url, $c_link=NULL) {
  $image = $this->remoteFetch($url);
  $filename = md5(mt_rand().$url);
  $newpath = ROOT_DIR.'/media/'.$filename;
  file_put_contents($newpath,$image);
  return $this->add($newpath, $c_link);
 }

 /**
  * Report image
  *
  * Allows reporting of problematic images so they may undergo review.
  *
  * @api
  * 
  * @param string $image The 6-digit id of an image.
  * @param int $type Number value representing the reason type which can be found in report/all
  */
 
 public function report($image, $type) {
  //Add report
  $this->res->add('report', $image, $type);
  //Hide image
  $sql = 'UPDATE images SET display=0 WHERE uid = :uid';
  $val = array (
   ':uid' => $image
  );
  $this->db->fetch($sql,$val);
  $message = 'A new image was reported on '. SITE_NAME . ".\n\n";
  $message .= "View Reported Image:\n";
  $message .= WEB_ROOT.'admin/image/'.$image."\n\n";
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
 
 /**
  * Random reported image
  *
  * Retrieves a random image that has been reported by users. Must be logged in as an admin to access this method.
  *
  * @api
  * 
  * @param string $sid Session ID that is provided when logged in. This is also set as a cookie. If sid cookie headers are sent, this value is not required.
  */
 
 public function reported($sid=NULL) {
  $current = $this->user->current($sid);
  if ($current['type'] < 2) throw new \Exception('Must be an admin to access method', 401); 
  $sql = 'SELECT * FROM resources WHERE type = "report" ORDER BY RAND() LIMIT 1;';
  $report_result = $this->db->fetch($sql);
  $image_result = $this->get($report_result[0]['image']);
  if (!$image_result) throw new \Exception('No image result', 404);
  return $image_result;
 }
 
 /**
  * Random unapproved image
  *
  * Retrieves a random unapproved image. Must be logged in as admin to access this method.
  *
  * @api
  *  
  * @param string $sid Session ID that is provided when logged in. This is also set as a cookie. If sid cookie headers are sent, this value is not required.
  */
 
 public function unapproved($sid=NULL) {
  $current = $this->user->current($sid);
  if ($current['type'] < 2) throw new \Exception('Must be an admin to access method', 401); 
  $sql = 'SELECT uid FROM images WHERE approved = 0 ORDER BY RAND() LIMIT 1;';
  $image = $this->db->fetch($sql);
  $image_result = $this->get($image[0]['uid']);
  if (!$image_result) throw new \Exception('No image result', 404);
  return $image_result;
 }

 /**
  * Random image
  *
  * Retrieves a random image.
  *
  * @api
  */
 
 public function random() {
  $sql = 'SELECT uid FROM images WHERE display = "1" ORDER BY RAND() LIMIT 1';
  $result = $this->db->fetch($sql);
  if (!$result) throw new \Exception('No image results', 404);
  $image = $this->get($result[0]['uid']);
  $image['response'] = $image['uid']; //backwards compatibility
  return $image;
 }
 
 public function recent() {
  $sql = 'SELECT * FROM `resources` WHERE type = "upload" ORDER BY created DESC';
  //this doesn't do anything yet
 }
 
 /**
  * Get image
  *
  * Retrieve information about an image.
  *
  * @api
  * 
  * @param string $image The 6-digit id of an image, or the filename of the image.
  * @param string $sid Session ID that is provided when logged in. This is also set as a cookie. If sid cookie headers are sent, this value is not required.
  */
 
 public function get($image, $sid=NULL) {
  $tag = new Tag;
  $current = $this->user->current($sid);
  #Get image data
  switch (strlen($image)) {
   case 6:	
    $sql = 'SELECT * from images WHERE uid = :image LIMIT 1;'; 	
   break;	
   default:
    $sql = 'SELECT * from images WHERE filename = :image LIMIT 1;';
   break;	
  }
  $val = array(
   ':image' => $image
  );
  $result = $this->db->fetch($sql,$val);
  //See if there was a result
  if (!$result) throw new \Exception('Image not found', 404);
  $result = $result[0];
  //Verify image isn't supposed to be hidden
  if (!$result['display'] AND !$this->user->isAdmin($sid)) throw new \Exception('Image removed', 403);
  //Get tags
  $tag_result = $tag->get($result['uid']);
  if (isset($tag_result)) $result['tags'] = $tag_result;
  //Get uploader
  $sql = 'SELECT * FROM resources WHERE (image = "'.$result['uid'].'" AND type = "upload" AND user_id IS NOT NULL)';
  $uploader = $this->db->fetch($sql);
  if (isset($uploader[0])) {
   $result['uploader'] = $this->user->get($uploader[0]['user_id']);
  }
  //Get resources
  if ($current) {
   $sql = 'SELECT * FROM resources WHERE (image = "'.$result['uid'].'" AND user_id = "'.$current['id'].'")';
   $resources = $this->db->fetch($sql);
   $data = NULL;
   foreach ($resources as $r) {
    $data[$r['type']] = $r;
   }
   if ($data) $result['data'] = $data;
   if (isset($data['save'])) $result['saved'] = 1;
  }
  //Page title
  $result['page_title'] = SITE_NAME;
  if (isset($result['tags'][0])) {
   $title_text = ' - ';
   foreach ($result['tags'] as $tag) {
    $title_text .= $tag['name'] . ', ';
   }
   $result['page_title'] .= rtrim($title_text, ', ');
  }
  //URLs
  $siteURL = $this->siteURL();
  $result['image_url'] = $siteURL['scheme'] .'://media.' . $siteURL['host']. '/media/'. $result['filename'];
  $result['thumb_url'] = $siteURL['scheme'] .'://thumbs.' . $siteURL['host']. '/media/thumbs/'. $result['filename'];
  $result['page_url'] = WEB_ROOT . $result['uid'];
  if ($this->user->isAdmin($sid)) {
   //Get report data
   $sql = 'SELECT * FROM resources WHERE type = "report" AND image = "' . $result['uid'] . '" LIMIT 1;';
   $report_result = $this->db->fetch($sql);
   if ($report_result) {
    $report = new Report;
    $report_type = $report->get($report_result[0]['value']);
    $report_result[0]['value'] = $report_type[0]['value'];
    $result['report'] = $report_result[0];
   }
  }
  return $result;
 }

 private function getUID($length = 6) {
  do {
   $uid = $this->generateUID($length);
   $sql = 'SELECT uid FROM images WHERE uid = "'.$uid.'" LIMIT 1';
   $not_unique = $this->db->fetch($sql);
  } while (count($not_unique));
  return $uid;
 }
 
 /**
  * Get stats
  *
  * Returns the total number of images, reported images and approved images.
  *
  * @api
  */
 
 public function stats() {
  $sql = 'SELECT (SELECT COUNT(*) from images) AS images,(SELECT COUNT(*) from resources WHERE type = "report") AS reports,(SELECT COUNT(*) from images WHERE approved = 1) AS approved';
  $result = $this->db->fetch($sql);
  return $result[0];
 }
 
 public function scale($image, $width = 240, $height = 160) {
  $imagedata = $this->get($image);
  $image = new Imagick(ROOT_DIR.'/media/'.$imagedata['filename']);

  foreach ($image as $frame) {
   $frame->thumbnailImage($width,$height,TRUE);
  }
  //header('Content-type: '.$image->getImageMimeType());
  return $image->getImagesBlob(); 
 }

 /**
  * Merge images
  *
  * Merge two images into one, merging any assoicated resources. Must be logged in as admin to use this method.
  *
  * @api
  * 
  * @param string $image1 The 6-digit id of an image.
  * @param string $image2 The 6-digit id of an image.
  * @param string $sid Session ID that is provided when logged in. This is also set as a cookie. If sid cookie headers are sent, this value is not required.
  */
 
 public function merge($image1, $image2, $sid=NULL) {
  $image = new Image();
  if (!$this->user->isAdmin($sid)) throw new \Exception('Must be an admin to access method', 401);
  $image1 = $image->get($image1);
  $image2 = $image->get($image2);
  $primary = $image1; $sec = $image2;
  //check total image area, assume the largest is the one to keep
  if (($image2['height']*$image2['width']) > ($image2['height']*$image2['width'])) {
   $primary = $image2;
   $sec = $image1;
  }
  $this->res->merge($primary['uid'], $sec['uid']);
  $this->remove($sec['uid']);
  return [
   'message' => 'Images merged.',
   'image' => $primary['uid'],
   'url' => $primary['page_url'],
   'thumb' => $primary['thumb_url']
  ];
 }
 
}

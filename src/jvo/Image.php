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
  if (!$current) throw new \Exception(_('Must be logged in to rate images'),1022);
  $query = new \Peyote\Delete('resources');
  $query->where('image', '=', $image)
        ->where('user_id', '=', $current['id'])
        ->where('type', '=', 'dislike');
  $this->db->fetch($query);
  $this->res->add('like', $image, $sid);
  return array(
   'message' => _('Image liked'),
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
  if (!$current) throw new \Exception(_('Must be logged in to rate images'),1023);
  $query = new \Peyote\Delete('resources');
  $query->where('image', '=', $image)
        ->where('user_id', '=', $current['id'])
        ->where('type', '=', 'like');
  $this->db->fetch($query);
  $this->res->add('dislike', $image, $sid);
  return array(
   'message' => _('Image disliked'),
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
  if (!$current) throw new \Exception(_('Must be logged in to save images'),1020);
  $this->res->add('save', $image, $sid);
  return array(
   'message' => _('Image saved'),
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
  if (!$current) throw new \Exception(_('Must be logged in to unsave images'),1021);
  $query = new \Peyote\Delete('resources');
  $query->where('image', '=', $image)
        ->where('user_id', '=', $current['id'])
        ->where('type', '=', 'save');
  $this->db->fetch($query);
  return array(
   'message' => _('Image unsaved'),
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
  if ($current['type'] < 2) throw new \Exception(_('Must be an admin to access method'), 401);
  if ($nsfw == TRUE) $nsfw = 1;
  $query = new \Peyote\Delete('resources');
  $query->where('image', '=', $image)
        ->where('type', '=', 'report');
  $this->db->fetch($query);
  $query = new \Peyote\Update('images');
  $query->set([
               'display' => 1,
               'approved' => 1,
               'nsfw' => $nsfw
              ])
        ->where('uid', '=', $image);
  $this->db->fetch($query);
  return array(
   'message' => _('Image approved')
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
  if ($current['type'] < 2) throw new \Exception(_('Must be an admin to access method'), 401);
  $query = new \Peyote\Select('images');
  $query->columns('filename')
        ->where('uid', '=', $image);
  $result = $this->db->fetch($query);
  $filename = $result[0]['filename'];
  //clean up resources
  $query = new \Peyote\Delete('resources');
  $query->where('image', '=', $image);
  $this->db->fetch($query);
  //remove image in db
  $query = new \Peyote\Delete('images');
  $query->where('uid', '=', $image);
  $this->db->fetch($query);
  //remove image
  unlink(ROOT_DIR.'/media/'.$filename);
  unlink(ROOT_DIR.'/media/thumbs/'.$filename);
  return array(
   'message' => _('Image removed')
  );
 }

 /**
  * Add image from upload
  *
  * Allows uploading images.
  *
  * @api
  * 
  * @param mixed $image An image uploaded as multi-part form data. Must be JPEG, PNG, or GIF format.
  * @param string $c_link An optional external link to comments for the image.
  * @param string $sid Session ID that is provided when logged in. This is also set as a cookie.
  */
 
 public function add($image, $c_link = NULL, $sid= NULL) {
  if (!$this->allow_upload()) throw new \Exception('Adding images is currently disabled due to site maintanence');
  $filename = md5(mt_rand());
  $path = ROOT_DIR.'/media/'.$filename;
  if (isset($image['tmp_name'])) move_uploaded_file($image['tmp_name'], $path);
  return $this->processAdd($path, $c_link, $sid);
 }
 
 /**
  * Process added image
  *
  * Once add() or addFromURL() have stored the image, this method completes adding it to the system
  * 
  * @param string $path Location the image is stored. Must be JPEG, PNG, or GIF format.
  * @param string $c_link An optional external link to comments for the image.
  * @param string $sid Session ID that is provided when logged in. This is also set as a cookie.
  */
 
 private function processAdd($path, $c_link=NULL, $sid = NULL) {
  $info = getimagesize($path);
  if (!$info) {
   unlink($path);
   throw new \Exception(_('Not a valid image'),1100);
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
  $uid = $this->getUID();
  $query = new \Peyote\Select('images');
  $query->where('hash', '=', $hash)
        ->limit(1);
  $result = $this->db->fetch($query);
  if ($result) {
   unlink($fullfilename);
   return array(
    'url' => WEB_ROOT.$result[0]['uid'],
    'uid' => $result[0]['uid'],
    'image' => WEB_ROOT.'media/'.$result[0]['filename'],
    'thumb' => WEB_ROOT.'media/thumbs/'.$result[0]['filename'],
    'message' => _('Duplicate image')
   );
  }
  else {
   $isAnimated = $this->isAnimated($filename, TRUE);
   if ($isAnimated) {
    $animated = 1;
   }
   else {
    $animated = 0;
   }
   $query = new \Peyote\Insert('images');
   $query->columns(['filename', 'uid', 'hash', 'type', 'width', 'height', 'c_link', 'animated'])
         ->values([$filename, $uid, $hash, $type, $width, $height, $c_link, $animated]);
   $s = $this->db->prepare($query->compile());
   $s->execute($query->getParams());//need to verify this was successful
   //create thumbnail
   $thumb = $this->scale($uid);
   file_put_contents(ROOT_DIR.'/media/thumbs/'.$filename,$thumb);
   //media resources
   $media = new Media;
   $media->add($uid, '/media/thumbs/' . $filename, 'thumb');
   $media->add($uid, '/media/' . $filename);
   //upload resource
   $this->res->add('upload', $uid, $sid, NULL, TRUE);
   return array(
    'url' => WEB_ROOT.$uid,
    'uid' => $uid,
    'image' => WEB_ROOT.'media/'.$filename,
    'thumb' => WEB_ROOT.'media/thumbs/'.$filename,
    'message' => _('Image added')
   );
  }
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
  * @param string $sid Session ID that is provided when logged in. This is also set as a cookie.
  */
 
 public function addFromURL($url, $c_link=NULL, $sid = NULL) {
  if (!$this->allow_upload()) throw new \Exception('Adding images is currently disabled due to site maintanence');
  $image = $this->remoteFetch($url);
  $filename = md5(mt_rand().$url);
  $newpath = ROOT_DIR.'/media/'.$filename;
  file_put_contents($newpath,$image);
  return $this->processAdd($newpath, $c_link, $sid);
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
  * @param string $sid Session ID that is provided when logged in. This is also set as a cookie.
  */
 
 public function report($image, $type, $sid = NULL) {
  if (!isset($image)) throw new \Exception(_('No image specified'));
  if (!isset($type)) throw new \Exception(_('No report type specified'));
  //Add report
  $this->res->add('report', $image, $sid, $type);
  //Hide image
  $query = new \Peyote\Update('images');
  $query->set(['display' => 0])
        ->where('uid', '=', $image);
  $this->db->fetch($query);
  $body = 'A new image was reported on '. SITE_NAME . ".\n\n";
  $body .= "View Reported Image:\n";
  $body .= WEB_ROOT.'admin/image/'.$image."\n\n";
  $body .= "IP:\n";
  $body .= $_SERVER['REMOTE_ADDR'];
  $message = new Mail();
  $message->sendAdminMessage('New Reported Image for '. SITE_NAME, $body);
  return array(
   'message' => _('Image Reported')
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
  if ($current['type'] < 2) throw new \Exception(_('Must be an admin to access method'), 401); 
  $query = new \Peyote\Select('resources');
  $query->where('type', '=', 'report')
        ->orderBy('RAND()')
        ->limit(1);
  $report_result = $this->db->fetch($query);
  $image_result = $this->get($report_result[0]['image']);
  if (!$image_result) throw new \Exception(_('No image result'), 404);
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
  if ($current['type'] < 2) throw new \Exception(_('Must be an admin to access method'), 401); 
  $query = new \Peyote\Select('images');
  $query->columns('uid')
        ->where('approved', '=', 0)
        ->orderBy('RAND()')
        ->limit(1);
  $image = $this->db->fetch($query);
  $image_result = $this->get($image[0]['uid']);
  if (!$image_result) throw new \Exception(_('No image result'), 404);
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
  $query = new \Peyote\Select('images');
  $query->columns('uid')
        ->where('display', '=', 1)
        ->orderBy('RAND()')
        ->limit(1);
  $result = $this->db->fetch($query);
  if (!$result) throw new \Exception(_('No image results'), 404);
  $image = $this->get($result[0]['uid']);
  $image['response'] = $image['uid']; //backwards compatibility
  return $image;
 }
 
  /**
  * Recently added images
  *
  * Displays a list of images recently added
  *
  * @api
  * 
  * @param int $count Number of results to display
  */
 
 public function recent($count = 25) {
  $query = new \Peyote\Select('resources');
  $query->where('type', '=', 'upload')
        ->orderBy('created', 'DESC')
        ->limit($count);
  $results = $this->db->fetch($query);
  $image = new Image();
  foreach ($results as $result) {
   try {
    $return[] = $image->get($result['image']);
   }
   catch(\Exception $e) {
    if ($e->getCode() != 403) {
     throw new \Exception($e);
    }
   }
  }
  return $return;
 }
 
  /**
  * Recently liked images
  *
  * Displays a list of images recently liked
  *
  * @api
  * 
  * @param int $count Number of results to display
  */
 
 public function recentLikes($count = 25) {
  $query = new \Peyote\Select('resources');
  $query->columns('image')
        ->where('type', '=', 'like')
        ->orderBy('created', 'DESC')
        ->groupBy('image')
        ->limit($count);
  $results = $this->db->fetch($query);
  $image = new Image();
  foreach ($results as $result) {
   try {
    $return[] = $image->get($result['image']);
   }
   catch(\Exception $e) {
    if ($e->getCode() != 403) {
     throw new \Exception($e);
    }
   }
  }
  return $return;
 }
 
 /**
  * Get image
  *
  * Retrieve information about an image.
  *
  * @api
  * 
  * @param string $image The 6-digit id of an image.
  * @param string $sid Session ID that is provided when logged in. This is also set as a cookie. If sid cookie headers are sent, this value is not required.
  * @param bool $brazzify Should Brazzzify.me url be returned
  */
 
 public function get($image, $sid=NULL, $brazzify = FALSE) {
  $current = $this->user->current($sid);
  //Get image data
  $query = new \Peyote\Select('images');
  $query->where('uid', '=', $image)
        ->limit(1);
  $result = $this->db->fetch($query);
  //See if there was a result
  if (!$result) { //check for merged image
   $query = new \Peyote\Select('resources');
   $query->columns('image')
         ->where('value', '=', $image)
         ->where('type', '=', 'merge');
   $result = $this->db->fetch($query);
   if ($result) {
    return ['merged_to' => $result[0]['image']];
   } else {
    throw new \Exception(_('Image not found'), 404);
   }
  }
  $result = $result[0];
  //Verify image isn't supposed to be hidden
  if (!$result['display'] AND !$this->user->isAdmin($sid)) throw new \Exception(_('Image removed'), 403);
  //Get media data
  $media = new Media;
  $media_results = $media->get($image);
  foreach ($media_results as $m) {
   $result['media'][$m['type']] = $m;
  }
  //Get tags
  $tag = new Tag;
  $tag_result = $tag->get($result['uid']);
  if (isset($tag_result)) $result['tags'] = $tag_result;
  //Get uploader
  $query = new \Peyote\Select('resources');
  $query->where('image', '=', $result['uid'])
        ->where('type', '=', 'upload');
  $upload = $this->db->fetch($query);
  if (isset($upload[0]['user_id'])) {
   $result['uploader'] = $this->user->get($upload[0]['user_id']);
  }
  if (isset($upload[0]['created'])) {
   $result['added'] = $upload[0]['created'];
  }
  //Get resources
  if ($current) {
   $query = new \Peyote\Select('resources');
   $query->where('image' ,'=', $result['uid'])
         ->where('user_id', '=', $current['id']);
   $resources = $this->db->fetch($query);
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
   $query = new \Peyote\Select('resources');
   $query->where('type', '=', 'report')
         ->where('image', '=', $result['uid'])
         ->limit(1);
   $report_result = $this->db->fetch($query);
   if ($report_result) {
    $report = new Report;
    $report_type = $report->get($report_result[0]['value']);
    $report_result[0]['value'] = $report_type[0]['value'];
    $result['report'] = $report_result[0];
   }
  }
  //Brazzify.me
  if ($brazzify) {
   $result['brazzify_url'] = json_decode($this->remoteFetch('http://i.brazzify.me/api.php?logo=brazzers&remote_url='.$result['image_url']),1)['url'];
  }
  return $result;
 }

 private function getUID($length = 6) {
  do {
   $uid = $this->generateUID($length);
   $query = new \Peyote\Select('images');
   $query->columns('uid')
         ->where('uid', '=', $uid)
         ->limit(1);
   $not_unique = $this->db->fetch($query);
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
  $result = [];
  $query = new \Peyote\Select('images');
  $query->columns('COUNT(*)');
  $result['images'] = $this->db->fetch($query)[0]['COUNT(*)'];
  $query = new \Peyote\Select('resources');
  $query->columns('COUNT(*)')
        ->where('type', '=', 'report');
  $result['reports'] = $this->db->fetch($query)[0]['COUNT(*)'];
  $query = new \Peyote\Select('images');
  $query->columns('COUNT(*)')
        ->where('approved', '=', 1);
  $result['approved'] = $this->db->fetch($query)[0]['COUNT(*)'];
  return $result;
 }
 
 public function scale($image, $width = 240, $height = 160) {
  $imagedata = $this->get($image);
  $image = new \Imagick(ROOT_DIR.'/media/'.$imagedata['filename']);
  
  $image = $image->coalesceImages();

  foreach ($image as $frame) {
   $frame->thumbnailImage($width,$height,TRUE);
   $frame->setImagePage($width, $height, 0, 0);
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
  if (!$this->user->isAdmin($sid)) throw new \Exception(_('Must be an admin to access method'), 401);
  $image1 = $image->get($image1);
  $image2 = $image->get($image2);
  $primary = $image1; $sec = $image2;
  //check total image area, assume the largest is the one to keep
  if (($image2['height']*$image2['width']) > ($image2['height']*$image2['width'])) {
   $primary = $image2;
   $sec = $image1;
  }
  $this->res->merge($primary['uid'], $sec['uid']);
  $this->res->add('merge', $primary['uid'], $sid, $sec['uid'], TRUE);
  $this->remove($sec['uid']);
  return [
   'message' => _('Images merged'),
   'image' => $primary['uid'],
   'url' => $primary['page_url'],
   'thumb' => $primary['thumb_url']
  ];
 }
 
  /**
  * Is Animated
  *
  * Checks to see if image is animated
  * 
  * @param string $image The 6-digit id of an image or image filename.
  * @param bool $search_by_filename If true, use filename instead of UID.
  */
 
 public function isAnimated($image, $search_by_filename = FALSE) {
  if (!$search_by_filename) {
   $imagedata = $this->get($image);
   $image = $imagedata['filename'];
  }
  $image = new Imagick(ROOT_DIR.'/media/'.$image);
  
  $animated = FALSE;
  $framecount = 0;
  foreach ($image as $frame) {
   $framecount++;
  }
  
  if ($framecount > 1) $animated = TRUE;
  return $animated;
 }
 
 private function allow_upload() {
  if (defined('DISABLE_UPLOAD')) {
   if (DISABLE_UPLOAD) {
    return FALSE;
   }
  }
  return TRUE;
 }
 
}

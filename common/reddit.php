<?php
require_once('../settings.inc');
require_once(ROOT_DIR.'/common/base.class.php');
require_once(ROOT_DIR.'/common/image.class.php');

class Reddit extends Base {

 protected $log;
 protected $logfile;
 protected $images;
 
 public function __construct() {
  parent::__construct();
  $this->image = new Image;
  $this->logfile = ROOT_DIR.'/tools/reddit.log';
 }

 public function process($subreddit) {
  $this->log("Beginning Subreddit ".$subreddit,$this->logfile);
  $posts = $this->getSubreddit($subreddit);
  foreach ($posts['data']['children'] as $post) {
   try {
    $this->checkScore($post);
    $data = $this->imgurProcess($post);
    $image = $this->addImage($data,$post);
    $this->log($image['message'].' '.$image['page'],$this->logfile);
   }
   catch(exception $e) {
    switch ($e->getCode()) {
     case '200':
      $this->log($e->getMessage(),$this->logfile);
     break;
     default:
      throw new Exception($e);
     break;
    }
   }
  }
 }

 private function imgurProcess($post) {
  $this->isImgur($post);
  $this->isImgurAlbum($post);
  $id = $this->getImgurID($post);
  return $this->getImageData($id);
 }

 private function getSubreddit($subreddit) {
  return json_decode($this->remoteFetch(array('url'=>'http://www.reddit.com/r/'.$subreddit.'.json?limit=100')),TRUE);
 }

 private function isImgurAlbum($post) {
  if (strpos($post['data']['url'],'imgur.com/a/') == TRUE) throw new Exception('Imgur album',200);
  return TRUE;
 }

 private function isImgur($post) {
  if (strpos($post['data']['domain'],'imgur') === FALSE) throw new Exception('Not an imgur image',200);
  return TRUE;
 }

 private function checkScore($post, $minScore=5) {
  if ($post['data']['score'] <= $minScore) throw new Exception('Score below '.$minScore,200);
  return TRUE;
 }

 private function getImgurID($post) {
  $data = explode('/',$post['data']['url']);
  $data = end($data);
  $data = explode('.',$data);
  $data = $data[0];
  if (strlen($data) > 5) throw new Exception('Unknown URL value: '.$data.' Full URL: '.$post['data']['url'],200);
  return $data;
 }

 private function getImageData($data) {
  $sql = 'SELECT id from imgur_history WHERE id = "'.$data.'"';
  if ($this->db->fetch($sql)) throw new Exception('Previously retrieved image ('.$data.')',200);
  $imagedata = json_decode($this->remoteFetch(array('url'=>'http://api.imgur.com/2/image/'.$data.'.json')),TRUE);
  if (!$imagedata) throw new Exception('Error retrieving Imgur data',200);
  if ($imagedata['error']) {
   if ($imagedata['error']['message'] == 'API limits exceeded') throw new Exception('Imgur API limits exceeded',999);
   throw new Exception('Imgur error: '.$imagedata['error']['message'].' '.$data,200);
  }
  $sql = 'INSERT INTO imgur_history(id) VALUES("'.$data.'")';
  $this->db->fetch($sql);
  return $imagedata;
 }

 private function addImage($imagedata, $post) {
  return $this->image->addImagefromURL(array(
   'url'=>$imagedata['image']['links']['original'],
   'c_link'=>'http://www.reddit.com'.$post['data']['permalink']
  ));
 }
 
}
?>
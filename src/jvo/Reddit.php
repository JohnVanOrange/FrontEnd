<?php
namespace JohnVanOrange\jvo;

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
   set_time_limit(60);
   try {
    $this->checkScore($post);
  	$url = $this->findURL($post);
    $image = $this->addImage($url,$post);
    $this->log($image['message'].' '.$image['url'],$this->logfile);
   }
   catch(\Exception $e) {
    switch ($e->getCode()) {
	 case '1100':
     case '200':
      $this->log($e->getMessage(),$this->logfile);
     break;
	 case '999':
	  throw new \Exception($e);
     default:
	  $this->log($e->getMessage(),$this->logfile);
     break;
    }
   }
  }
  return $this->getLogs();
 }

 private function findURL($post) {
  if ($this->isImgur($post) !== FALSE) return $this->imgurProcess($post);
  if ($this->isDirectImage($post) !== FALSE) return $this->directImageProcess($post);
  if ($this->isQuickMeme($post) !== FALSE) return $this->quickMemeProcess($post);
  throw new \Exception('Not a known image type. URL: '.$post['data']['url'],200);
 }

 private function isQuickMeme($post) {
  if (strpos($post['data']['domain'],'quickmeme') !== FALSE) return TRUE;
  return strpos($post['data']['domain'],'qkme');
 }

 private function quickMemeProcess($post) {
  $parts = explode('/',rtrim($post['data']['url'],'/'));
  $last = end($parts);
  return 'http://i.qkme.me/'.$last.'.jpg';
 }
 
 private function isDirectImage($post) {
  $parts = explode('.',$post['data']['url']);
  $last = end($parts);
  $valid = array(
   'jpg',
   'jpeg',
   'png',
   'gif'
  );
  return array_search(strtolower($last),$valid);
 }
 
 private function directImageProcess($post) {
  return $post['data']['url'];
 }
 
 private function imgurProcess($post) {
  $this->isImgurAlbum($post);
  $id = $this->getImgurID($post);
  return $this->getImageData($id);
 }

 private function getSubreddit($subreddit) {
  return json_decode($this->remoteFetch('http://www.reddit.com/r/'.$subreddit.'.json?limit=100'),TRUE);
 }

 private function isImgurAlbum($post) {
  if (strpos($post['data']['url'],'imgur.com/a/') == TRUE) throw new \Exception('Imgur album',200);
  return TRUE;
 }

 private function isImgur($post) {
  return strpos($post['data']['domain'],'imgur');
 }

 private function checkScore($post, $minScore=5) {
  if ($post['data']['score'] <= $minScore) throw new \Exception('Score below '.$minScore,200);
  return TRUE;
 }

 private function getImgurID($post) {
  $data = explode('/',$post['data']['url']);
  $data = end($data);
  $data = explode('.',$data);
  $data = $data[0];
  if (strlen($data) > 7) throw new \Exception('Unknown URL value: '.$data.' Full URL: '.$post['data']['url'],200);
  return $data;
 }

 private function getImageData($data) {
  $query = new \Peyote\Select('imgur_history');
  $query->columns('id')
	->where('id', '=', $data);
  if ($this->db->fetch($query)) throw new \Exception('Previously retrieved image ('.$data.')',200);
  $imagedata = json_decode($this->remoteFetch(
   'https://api.imgur.com/3/image/'.$data.'.json',
   array('Authorization: Client-ID '.IMGUR_CID)
   ),TRUE);
  $imagedata = $imagedata['data'];
  if (!$imagedata) throw new \Exception('Error retrieving Imgur data',200);
  if ($imagedata['error']) {
   if ($imagedata['error'] == 'User request limit exceeded') throw new \Exception('Imgur API limits exceeded',999);
   throw new \Exception('Imgur error: '.$imagedata['error'].' '.$data,200);
  }
  $query = new \Peyote\Insert('imgur_history');
  $query->columns(['id'])
	->values([$data]);
  $this->db->fetch($query);
  return $imagedata['link'];
 }

 private function addImage($url, $post) {
  return $this->image->addFromURL($url, 'http://www.reddit.com'.$post['data']['permalink']);
 }
 
}
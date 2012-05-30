<?
require_once('../settings.inc');
require_once(ROOT_DIR.'/common/api.class.php');

class Reddit extends API {
 public function process($subreddit) {
  echo "Beginning Subreddit ".$subreddit."\n";
  $posts = $this->getSubreddit($subreddit);
  foreach ($posts['data']['children'] as $post) {
   try {
    $this->isImgur($post);
    $this->checkScore($post);
    $id = $this->getImgurID($post);
    $data = $this->getImageData($id);
    $image = $this->addImage($data,$post);
    echo $id.' '.$image['message'].' '.$image['page']."\n";
   }
   catch(exception $e) {
    if ($e->getCode() === 999) throw new Exception('Aborting, Imgur API Limits Exceeded.');
    echo $e->getMessage()."\n";
   }
  }
 }

 private function getSubreddit($subreddit) {
  return json_decode($this->remoteFetch(array('url'=>'http://www.reddit.com/r/'.$subreddit.'.json?limit=100')),TRUE);
 }

 private function isImgur($post) {
  if (strpos($post['data']['domain'],'imgur') === FALSE) throw new Exception('Not an imgur image');
  return TRUE;
 }

 private function checkScore($post, $minScore=5) {
  if ($post['data']['score'] <= $minScore) throw new Exception('Score below '.$minScore);
  return TRUE;
 }

 private function getImgurID($post) {
  $data = explode('/',$post['data']['url']);
  $data = end($data);
  $data = explode('.',$data);
  $data = $data[0];
  if (strlen($data) > 5) throw new Exception('Unknown URL value: '.$data.' Full URL: '.$post['data']['url']);
  return $data;
 }

 private function getImageData($data) {
  $sql = 'SELECT id from imgur_history WHERE id = "'.$data.'"';
  if ($this->db->fetch($sql)) throw new Exception('Previously retrieved image ('.$data.')');
  $imagedata = json_decode($this->remoteFetch(array('url'=>'http://api.imgur.com/2/image/'.$data.'.json')),TRUE);
  if ($imagedata['error']) {
   if ($imagedata['error']['message'] == 'API limits exceeded') throw new Exception('Imgur API limits exceeded',999);
   throw new Exception('Imgur error: '.$imagedata['error']['message']);
  }
  $sql = 'INSERT INTO imgur_history(id) VALUES("'.$data.'")';
  $this->db->fetch($sql);
  return $imagedata;
 }

 private function addImage($imagedata, $post) {
  return $this->addImagefromURL(array(
   'url'=>$imagedata['image']['links']['original'],
   'c_link'=>'http://www.reddit.com'.$post['data']['permalink']
  ));
 }

}
?>

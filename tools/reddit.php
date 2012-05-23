<?
require_once('../settings.inc');
require_once(ROOT_DIR.'/common/api.class.php');

class RemoteFiles extends API {
 public function get($options=array()) {
  return $this->remoteFetch($options);
 }
}

$api = new RemoteFiles();
$posts = json_decode($api->get(array('url'=>'http://www.reddit.com/r/funny.json')));

foreach($posts->data->children as $post) {
 if (strpos($post->data->domain,'imgur') !== FALSE) {
  if ($post->data->score >= 5) {
   //actually process the image
   $data = explode('/',$post->data->url);
   $data = end($data);
   $data = explode('.',$data);
   $data = $data[0];  
   $imagedata = json_decode($api->get(array('url'=>'http://api.imgur.com/2/image/'.$data.'.json')));
   $result = $api->addImagefromURL(array(
    'url'=>$imagedata->image->links->original,
    'c_link'=>'http://www.reddit.com'.$post->data->permalink
   ));
   echo $result['message'].' '.$result['page']."\n";
  }
  else {
   echo "Score was less than 5\n";
  } 
 }
 else {
  echo "Not an imgur post\n";
 }
}

?>

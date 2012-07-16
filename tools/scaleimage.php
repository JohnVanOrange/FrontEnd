<?php
require_once('../settings.inc');
require_once(ROOT_DIR.'/classes/image.class.php');

$db = new DB('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
$image = new Image;
$sql = 'SELECT filename,uid FROM images WHERE display=1';
$results = $db->fetch($sql);
foreach ($results as $result) {
 echo 'Preparing image '.$result['filename']."\n";
 try {
  $thumb = @$image->scale(array('image'=>$result['uid']));
  file_put_contents(ROOT_DIR.'/media/thumbs/'.$result['filename'],$thumb);
  echo 'Thumb created for '.$result['filename']."\n";
 }
 catch(ImagickException $e) {
  $log = $result['filename'].' '.$e->getMessage()."\n";
  file_put_contents('thumb.log',$log,FILE_APPEND);
  echo 'Error saving thumb for '.$result['filename']."\n";
 }
}
?>
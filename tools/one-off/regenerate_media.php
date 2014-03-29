<?php
// 03/14/2014
namespace JohnVanOrange\jvo;

require_once('../../vendor/autoload.php');
require_once('../../settings.inc');

$db = new DB('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
$media = new Media;
$start = $argv[1] - 1;
$counter = $start;

$query = new \Peyote\Select('media');
$query->columns('uid', 'file')
      ->where('type', '=', 'primary')
      ->limit(100000, $start)
      ->orderBy('uid');
$results = $db->fetch($query);
foreach ($results as $result) {
  $counter++;
  //remove current media resources
  $query = new \Peyote\Delete('media');
  $query->where('uid', '=', $result['uid']);
  $db->fetch($query);
  try {
    $m = explode('/', $result['file']);
    $file = '/media/thumbs/' . $m[2];
    $media->add($result['uid'], $file, 'thumb');
  }
  catch(\ImagickException $e) {
    $message = new Mail();
    $message->sendAdminMessage(SITE_NAME . ' failed to add a thumb media resource', 'Image: ' . $result['uid']);
  }
  try {
    $media->add($result['uid'], $result['file']);
  }
  catch(\ImagickException $e) {
    $message = new Mail();
    $message->sendAdminMessage(SITE_NAME . ' failed to add a PRIMARY media resource', 'Image: ' . $result['uid']);
  }
  echo 'Added media resources: ' . $counter . ' ' . $result['uid'] . "\n";
}
echo "\n\n";
echo "Process complete!\n";
echo $counter . " total images\n";
?>

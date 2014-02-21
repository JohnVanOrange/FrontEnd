<?php
namespace JohnVanOrange\jvo;

require_once('../../vendor/autoload.php');
require_once('../../settings.inc');

$db = new DB('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
$media = new Media;
$start = $argv[1] - 1;
$counter = $start;

$query = new \Peyote\Select('images');
$query->columns('uid', 'filename')
      ->where('display', '=', 1)
      ->limit(100000, $start);

$results = $db->fetch($query);
foreach ($results as $result) {
  $counter++;
  try {
    $media->add($result['uid'], '/media/thumbs/' . $result['filename'], 'thumb');
  }
  catch(\ImagickException $e) {
    $message = new Mail();
    $message->sendAdminMessage(SITE_NAME . ' failed to add a thumb media resource', 'Image: ' . $result['uid']);
  }
  try {
    $media->add($result['uid'], '/media/' . $result['filename']);
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
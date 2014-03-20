<?php
// 03/20/2014
namespace JohnVanOrange\jvo;

require_once('../../vendor/autoload.php');
require_once('../../settings.inc');

$db = new DB('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
$media = new Media;
$counter = 0;

$query = new \Peyote\Select('media');
$query->columns('COUNT(uid)','uid', 'file')
      ->groupBy('uid')
      ->having('COUNT(uid)', '=', 1);
$results = $db->fetch($query);
foreach ($results as $result) {
  $counter++;
  try {
    $m = explode('/', $result['file']);
    $file = '/media/thumbs/' . $m[2];
    $media->add($result['uid'], $file, 'thumb');
  }
  catch(\ImagickException $e) {
    $message = new Mail();
    $message->sendAdminMessage(SITE_NAME . ' failed to add a thumb media resource', 'Image: ' . $result['uid']);
  }
  echo 'Fixed media resource: ' . $counter . ' ' . $result['uid'] . "\n";
}
echo "\n\n";
echo "Process complete!\n";
echo $counter . " total images\n";
?>

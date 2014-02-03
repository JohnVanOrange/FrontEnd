<?php
namespace JohnVanOrange\jvo;

require_once('../../vendor/autoload.php');
require_once('../../settings.inc');

$db = new DB('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
$image = new Image;

$query = new \Peyote\Select('images');
$query->columns('uid')
      ->where('display', '=', 1);
$results = $db->fetch($query);
$counter = 0;
foreach ($results as $result) {
 $counter++;
 if ($image->isAnimated($result['uid'])) {
  $query = new \Peyote\Update('images');
  $query->set([
               'animated' => 1
              ])
        ->where('uid', '=', $result['uid']);
  $db->fetch($query);
  echo 'Count: ' . $counter . ' ' . $result['uid'] . " WAS animated.\n";
 } else {
  echo 'Count: ' . $counter . ' ' . $result['uid'] . " not animated.\n";
 }
}
echo "\n\n";
echo "Process complete!\n";
echo $counter . " total images\n";
?>
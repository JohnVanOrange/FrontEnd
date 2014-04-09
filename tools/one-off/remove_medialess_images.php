<?php
//04/09/2014
namespace JohnVanOrange\core;

require_once('../../vendor/autoload.php');
require_once('../../settings.inc');

$db = new DB('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
$image = new Image;
$start = $argv[1] - 1;
$counter = $start;
$counter_missing = 0;
$counter_good = 0;

echo "Admin session ID: ";
$handle = fopen ("php://stdin","r");
$sid = trim(fgets($handle));

$query = new \Peyote\Select('images');
$query->columns('uid')
      ->limit(100000, $start)
      ->orderBy('uid');
$results = $db->fetch($query);
foreach ($results as $result) {
  $counter++;
  $image_result = @$image->get($result['uid'], $sid);
  if (!$image_result['media']) {
   @$image->remove($result['uid'], $sid);
   echo 'NO IMAGE MEDIA!!!!! ' . $result['uid'] . "\n";
   $counter_missing++;
  }
  else {
   echo 'Media fine ' . $result['uid'] . "\n";
   $counter_good++;
  }
}
echo "\n\n";
echo "Process complete!\n";
echo $counter . " total images\n";
echo $counter_good . " good images\n";
echo $counter_missing . " missing/removed images\n";
?>
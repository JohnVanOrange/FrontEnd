<?php
namespace JohnVanOrange\jvo;

require_once('../../vendor/autoload.php');
require_once('../../settings.inc');

$db = new DB('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
$image = new Image;

$counter = 0;

$query = new \Peyote\Select('media');
$query->columns('uid, file')
      ->where('width', '=', 1)
      ->where('type', '=', 'primary');
$results = $db->fetch($query);

foreach ($results as $result) {
 $counter++;
 //remove current primary resources
 $query = new \Peyote\Delete('media');
 $query->where('uid', '=', $result['uid'])
       ->where('type', '=', 'primary');
 $db->fetch($query);
 
 //recreate media resource
 $media = new Media;
 $media->add($result['uid'], $result['file']);
 
 echo 'Count: ' . $counter . ' ' . $result['uid'] . " fixed.\n";
}
echo "\n\n";
echo "Process complete!\n";
echo $counter . " total images\n";
?>
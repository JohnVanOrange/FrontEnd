<?php
// Created 09/24/2015
namespace JohnVanOrange\jvo;

require_once('../../vendor/autoload.php');
require_once('../../settings.inc');

$db = new \JohnVanOrange\API\DB('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
$image = new \JohnVanOrange\API\Image;
$query = new \Peyote\Select('media');
$query->columns('uid')
			->where('format', '=', 'gif')
			->where('type', '=', 'primary')
			->limit('5000');
$results = $db->fetch($query);
$counter = 0;

echo "Admin session ID: ";
$handle = fopen ("php://stdin","r");
$sid = trim(fgets($handle));

foreach ($results as $result) {
	$counter++;
	try {
		$image->remove($result['uid'], $sid);
	}
	catch (\Exception $e) {
		echo "Error when trying to remove image: " . $result['uid'] . ' ';
		echo $e->getMessage() . "\n";
	}
	echo 'Removed ' . $result['uid']. "\n";
}
echo "\n\n";
echo "Process complete!\n";
echo $counter . " total images.\n";
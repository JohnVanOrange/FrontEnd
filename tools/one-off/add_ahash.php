<?php
	namespace JohnVanOrange\jvo;
	
	require_once('../../vendor/autoload.php');
	require_once('../../settings.inc');
 
	$db = new DB('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
	$ddb = new \PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
	$image = new Image;
	$query = new \Peyote\Select('images');
	$query->columns('filename, uid')
				->where('display', '=', 1);
				//->where('ahash', 'IS', NULL);
	$results = $db->fetch($query);
	$counter = 0;
	$total = count($results);
	foreach ($results as $result) {
		$counter++;
		$imagedata = $image->get($result['uid']);
		$ahash = $image->ahash($imagedata);
		echo $ahash ."\n";
		$sql = "UPDATE images SET ahash=(x'".$ahash."') WHERE uid='".$imagedata['uid']."';";
		$s = $ddb->prepare($sql);
		$s->execute();
		echo $imagedata['uid']." updated. (". $counter ." of ". $total .")\n";
	}
	echo "\n\n";
	echo "Process complete!\n";
	
	
	//select conv(hex(ahash),16,2) from images
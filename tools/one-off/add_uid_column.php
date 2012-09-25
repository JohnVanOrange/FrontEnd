<?php
require_once('../settings.inc');
require_once(ROOT_DIR.'/common/api.class.php');
$db = new DB('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
$api = new API;

$sql = 'SELECT filename FROM images';

$results = $db->fetch($sql);

foreach ($results as $result) {
 $uid = $api->getUID();
 $sql = 'UPDATE images SET uid="'.$uid.'" WHERE filename="'.$result['filename'].'"';
 $db->fetch($sql);
} 
?>

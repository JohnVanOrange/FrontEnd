<?php
require_once('../settings.inc');
require_once(ROOT_DIR.'/classes/db.class.php');
$db = new DB('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);

$sql = 'SELECT filename FROM images';

$results = $db->fetch($sql);

foreach ($results as $result) {
 $parts = explode('.',$result['filename']);
 $sql = 'UPDATE images SET name="'.$parts[0].'" WHERE filename="'.$result['filename'].'"';
 $db->fetch($sql);
} 
?>

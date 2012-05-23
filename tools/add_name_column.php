<?php
require_once('../settings.inc');
require_once(ROOT_DIR.'/common/db.php');


$sql = 'SELECT filename FROM images';

$results = $db->fetch($sql);

foreach ($results as $result) {
 $parts = explode('.',$result['filename']);
 $sql = 'UPDATE images SET name="'.$parts[0].'" WHERE filename="'.$result['filename'].'"';
 $db->fetch($sql);
} 
?>

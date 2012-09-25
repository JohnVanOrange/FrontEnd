<?php
require_once('../settings.inc');
require_once(ROOT_DIR.'/classes/base.class.php');

$db = new DB('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);

$sql = 'SELECT * FROM tags';
$tags = $db->fetch($sql);

foreach($tags as $tag) {
 $sql = 'SELECT uid FROM images WHERE id = '.$tag['image_id'];
 $image = $db->fetch($sql);
 $sql = 'INSERT INTO resources (image, value, type) VALUES("'.$image[0]['uid'].'", "'.$tag['tag_id'].'", "tag")';
 $db->fetch($sql);
 echo '.';
}
?>
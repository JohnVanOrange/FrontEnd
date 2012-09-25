<?php
require_once('../settings.inc');
require_once(ROOT_DIR.'/common/tag.class.php');
require_once('simple_html_dom.php');

$db = new DB('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
$tag = new Tag;
$sql = 'SELECT id FROM tag_list WHERE basename = ""';
$results = $db->fetch($sql);
foreach ($results as $result) {
 $sql = 'DELETE FROM tag_list WHERE id = '.$result['id'];
 $results = $db->fetch($sql);
 $sql = 'DELETE FROM tags WHERE tag_id = '.$result['id'];
 $results = $db->fetch($sql);
}

?>

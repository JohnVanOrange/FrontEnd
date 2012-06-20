<?php
require_once('../settings.inc');
require_once(ROOT_DIR.'/common/tag.class.php');
require_once('simple_html_dom.php');

$db = new DB('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
$tag = new Tag;
$sql = 'SELECT name FROM tag_list';
$results = $db->fetch($sql);
foreach ($results as $result) {
 $slug = $tag->text2slug($result['name']);
 $sql = 'UPDATE tag_list SET basename="'.$slug.'" WHERE name="'.addslashes($result['name']).'"';
 $results = $db->fetch($sql);
 echo 'Slug updated: '.$slug."\n";
}

?>

<?php
require_once('../settings.inc');
require_once(ROOT_DIR.'/classes/db.class.php');
$db = new DB('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);

$sql = "SELECT hash FROM `images` WHERE display='1' GROUP BY hash HAVING count(*) > 1;";

$results = $db->fetch($sql);

foreach ($results as $result) {
 //using api, report the first duplicate with a value of 5
}

?>

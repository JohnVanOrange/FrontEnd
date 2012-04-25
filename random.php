<?php
require_once('settings.inc');

require_once(ROOT_DIR.'/common/db.php');
$db = new DB('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);

$sql = "SELECT filename FROM images WHERE display = '1' ORDER BY RAND() LIMIT 1";
$result = $db->fetch($sql);

header('Location: '.WEB_ROOT.'display/'.$result[0]['filename']);
?>

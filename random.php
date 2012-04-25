<?php
require_once('settings.inc');
require_once('common/db.php');

$sql = "SELECT filename FROM images WHERE display = '1' ORDER BY RAND() LIMIT 1";
$result = $db->fetch($sql);

header('Location: '.WEB_ROOT.'display/'.$result[0]['filename']);
?>

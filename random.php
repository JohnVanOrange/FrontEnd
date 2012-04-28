<?php
require_once('settings.inc');
require_once('common/api.class.php');

$api = new API;

$result = $api->randomImage();

header('Location: '.WEB_ROOT.'display/'.$result['filename']);
?>

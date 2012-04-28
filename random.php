<?php
require_once('settings.inc');
require_once('api/api.class.php');

$api = new API;

$result = $api->randomImage();

header('Location: '.WEB_ROOT.'display/'.$result['filename']);
?>

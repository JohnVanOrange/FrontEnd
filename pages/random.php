<?php
require_once('../settings.inc');
require_once('../common/api.class.php');

$api = new API;

header('Location: '.WEB_ROOT.'display/'.$api->randomImage());
?>

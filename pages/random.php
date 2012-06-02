<?php
require_once('common/api.class.php');

$api = new API;

header('Location: '.WEB_ROOT.'v/'.$api->randomImage());
?>

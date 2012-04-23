<?php
require_once('settings.inc');

$dir = ROOT_DIR.'/media/';
$files = glob($dir.'*.*');
$file = array_rand($files);
$image = end(explode('/',$files[$file]));

header('Location: '.WEB_ROOT.'display/'.$image);
?>

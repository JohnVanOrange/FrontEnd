<?php
require_once('common/image.class.php');

$image = new Image;

header('Location: '.WEB_ROOT.'v/'.$image->random());
?>

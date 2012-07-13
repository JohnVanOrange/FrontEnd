<?php
require('smarty.php');
require_once('classes/image.class.php');

$image = new Image;

$template = 'saved.tpl';

$tpl->assign('images',$image->saved());

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");
$tpl->display($template);
?>
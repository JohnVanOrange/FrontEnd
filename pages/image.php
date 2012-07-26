<?php
require_once('smarty.php');

$template = 'image.tpl';

$result = call('image/get',array('image'=>$image_name));

$tpl->assign('image', WEB_ROOT.'media/'.$result['filename']);

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");
header ('Access-Control-Allow-Origin: *');
$tpl->display($template);
?>
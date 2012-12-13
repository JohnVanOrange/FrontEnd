<?php
require_once('smarty.php');

$template = 'display.tpl';

$image_name = $request[1];

$tpl->assign('image', call('image/get',array('image'=>$image_name)));

$tpl->assign('rand',md5(uniqid(rand(), true)));
$tpl->assign('report_types',call('report/all'));

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");
$tpl->display($template);
?>

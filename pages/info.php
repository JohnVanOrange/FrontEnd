<?php
require_once('smarty.php');

$template = 'info.tpl';

$uid = $request[1];

$image = call('image/get',array('image'=>$uid));

$tpl->assign('image',$image);

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");
$tpl->display($template);
?>
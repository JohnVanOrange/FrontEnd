<?php
require_once('common/smarty.php');
require_once('common/api.class.php');

$api = new API;

$template = 'display.tpl';

try {
 $result = $api->getImage(array('image'=>$image));
}
catch (exception $e) {
 page_exception_handler($e);
}

$tpl->assign('image', WEB_ROOT.'media/'.$result['filename']);
$tpl->assign('image_name', $result['filename']);
$tpl->assign('web_root',WEB_ROOT);
$tpl->assign('type',$result['type']);
$tpl->assign('width',$result['width']);
$tpl->assign('height',$result['height']);

if ($_COOKIE['theme']) {
 $tpl->assign('theme',$_COOKIE['theme']);
}
else {
 $tpl->assign('theme','light');
}

$tpl->assign('brazzify',FALSE);
if (isset($brazzify)) $tpl->assign('brazzify',TRUE);

$tpl->display($template);

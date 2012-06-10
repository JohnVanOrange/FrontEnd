<?php
require_once('common/smarty.php');
require_once('common/image.class.php');

$image = new Image;

$template = 'display.tpl';

try {
 $result = $image->getImage(array('image'=>$image_name));
}
catch (exception $e) {
 page_exception_handler($e);
}

$tpl->assign('image', WEB_ROOT.'media/'.$result['filename']);
$tpl->assign('image_name', $result['filename']);
$tpl->assign('image_id',$result['id']);
$tpl->assign('uid',$result['uid']);
$tpl->assign('type',$result['type']);
$tpl->assign('width',$result['width']);
$tpl->assign('height',$result['height']);
$tpl->assign('rand',md5(uniqid(rand(), true)));
if ($result['tags']) $tpl->assign('tags',$result['tags']);
if ($result['c_link']) $tpl->assign('c_link', $result['c_link']);

$tpl->assign('brazzify',FALSE);
if (isset($brazzify)) $tpl->assign('brazzify',TRUE);

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");
$tpl->display($template);
?>

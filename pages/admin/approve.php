<?php
require_once('smarty.php');

$template = 'approve.tpl';

$image = call('image/unapproved');
$tpl->assign('image',$image);
$tpl->assign('image_loc', WEB_ROOT.'media/'.$image['filename']);
$tpl->assign('rand',md5(uniqid(rand(), true)));

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");
$tpl->display($template);
?>
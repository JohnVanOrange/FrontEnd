<?php
require_once('settings.inc');
require_once('common/smarty.php');
require_once('common/db.php');

$template = 'display.tpl';

//should do some error handling when the images are set to display = 0
$sql = "SELECT * FROM images WHERE filename = :filename LIMIT 1;";
$val = array(
 ':filename' => $_GET[image]
);
$result = $result = $db->fetch($sql, $val);
$result = $result[0];

//TODO: check to make sure there is a result

$tpl->assign('image', WEB_ROOT.'media/'.$result['filename']);
$tpl->assign('web_root',WEB_ROOT);
$tpl->assign('type',$result['type']);
$tpl->assign('width',$result['width']);
$tpl->assign('height',$result['height']);

$tpl->display($template);

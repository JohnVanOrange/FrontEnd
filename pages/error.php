<?php
require('common/smarty.php');

$template = 'error.tpl';

header("HTTP/1.0 ".$number);
$_SERVER['REDIRECT_STATUS'] = $number;

$tpl->assign('number',$number);
$tpl->assign('web_root',WEB_ROOT);

if ($_COOKIE['theme']) {
 $tpl->assign('theme',$_COOKIE['theme']);
}
else {
 $tpl->assign('theme','light');
}

$tpl->display($template);

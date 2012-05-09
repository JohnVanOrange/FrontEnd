<?php
require_once('common/smarty.php');
require_once('common/api.class.php');

$template = 'tos.tpl';

if ($_COOKIE['theme']) {
 $tpl->assign('theme',$_COOKIE['theme']);
}
else {
 $tpl->assign('theme','light');
}

header("Content-type: text/html; charset=UTF-8");
$tpl->display($template);

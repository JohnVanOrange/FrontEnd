<?php
require('smarty.php');

$template = 'exception.tpl';

$tpl->assign('code',$e->getCode());
$tpl->assign('message',$e->getMessage());

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");
$tpl->display($template);
?>
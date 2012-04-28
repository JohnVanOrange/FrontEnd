<?php
require_once('common/smarty.php');

$template = 'error.tpl';

$tpl->assign('number',$number);
$tpl->assign('web_root',WEB_ROOT);

$tpl->display($template);

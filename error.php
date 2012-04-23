<?php
require_once('settings.inc');
require_once('common/common.php');

$template = 'error.tpl';

$tpl->assign('number',$_GET[number]);
$tpl->assign('web_root',WEB_ROOT);

$tpl->display($template);

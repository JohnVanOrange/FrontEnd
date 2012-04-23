<?php
require_once('settings.inc');
require_once('common/common.php');

$template = 'display.tpl';

$tpl->assign('image',WEB_ROOT.'media/'.$_GET[image]);
$tpl->assign('web_root',WEB_ROOT);

$tpl->display($template);

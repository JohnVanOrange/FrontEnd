<?php
require_once('smarty.php');

$template = 'privacy.tpl';

$tpl->assign('title_text', 'Privacy Policy');

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");
$tpl->display($template);
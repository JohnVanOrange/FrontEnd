<?php
require('smarty.php');

$template = 'jvon.tpl';

require_once('common.php');

$tpl->assign('title_text', 'John VanOrange Network');

header("Content-type: text/html; charset=UTF-8");
$tpl->display($template);
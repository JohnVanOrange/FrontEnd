<?php
require_once('smarty.php');

$template = 'form.tpl';

$tpl->assign('form', $route->get_data(0));

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");
$tpl->display($template);
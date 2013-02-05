<?php
require('smarty.php');

$template = 'jvon.tpl';

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");
$tpl->display($template);
?>
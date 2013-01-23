<?php
require_once('smarty.php');

$template = 'webapp.tpl';

require_once('common.php');

header("Content-type: application/x-web-app-manifest+json; charset=UTF-8");
$tpl->display($template);
?>
<?php
require_once('common/smarty.php');

$template = 'upload.tpl';

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");
$tpl->display($template);
?>

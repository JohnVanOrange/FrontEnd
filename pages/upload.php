<?php
require_once('smarty.php');

$template = 'upload.tpl';

$tpl->assign('title_text', 'Upload Images');

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");
$tpl->display($template);
?>

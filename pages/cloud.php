<?php
require('smarty.php');

$template = 'cloud.tpl';

$tpl->assign('tags', call('tag/top'));

$tpl->assign('title_text','Tag Cloud');

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");
$tpl->display($template);

?>
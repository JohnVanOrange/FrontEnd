<?php
require('smarty.php');

$template = 'saved.tpl';

$tpl->assign('images',call('image/saved'));

$tpl->assign('title_text', 'Saved Images');

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");
$tpl->display($template);
?>
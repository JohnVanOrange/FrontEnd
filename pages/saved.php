<?php
require('smarty.php');

$template = 'saved.tpl';

$username = $request[1];

$tpl->assign('images',call('image/saved',array('username'=>$username)));

$tpl->assign('title_text', 'Saved Images');

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");
$tpl->display($template);
?>
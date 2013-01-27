<?php
require('smarty.php');

$template = 'uploads.tpl';

$username = $request[1];

$tpl->assign('images',call('image/uploaded',array('username'=>$username)));

$tpl->assign('title_text', 'Uploaded Images');

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");
$tpl->display($template);
?>
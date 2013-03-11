<?php
require('smarty.php');

$template = 'user.tpl';

$username = $request[1];

$tpl->assign('profile', call('user/get',array('value'=>$username,'search_by'=>'username')));

$tpl->assign('title_text', 'Profile for ' . $username);

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");
$tpl->display($template);
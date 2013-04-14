<?php
namespace JohnVanOrange\jvo;

require('smarty.php');

$api = new API();

$template = 'user.tpl';

$username = $request[1];

$tpl->assign('profile', $api->call('user/get',array('value'=>$username,'search_by'=>'username')));

$tpl->assign('title_text', 'Profile for ' . $username);

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");
$tpl->display($template);
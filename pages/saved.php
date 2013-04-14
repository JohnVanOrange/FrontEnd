<?php
namespace JohnVanOrange\jvo;

require('smarty.php');

$api = new API();

$template = 'saved.tpl';

$username = $request[1];

$tpl->assign('images',$api->call('user/saved',array('username'=>$username)));

$tpl->assign('title_text', 'Saved Images');

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");
$tpl->display($template);
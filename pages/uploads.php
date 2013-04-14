<?php
namespace JohnVanOrange\jvo;

require('smarty.php');

$api = new API();

$template = 'uploads.tpl';

$username = $route->get_data(0);

$tpl->assign('images',$api->call('user/uploaded',array('username'=>$username)));

$tpl->assign('title_text', 'Uploaded Images');

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");
$tpl->display($template);
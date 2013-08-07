<?php
namespace JohnVanOrange\jvo;

require_once('smarty.php');

$api = new API();

$template = 'info.tpl';

$uid = $route->get_data(0);

$image = $api->call('image/get',array('image'=>$uid));

$tpl->assign('image',$image);

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");
$tpl->display($template);
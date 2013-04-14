<?php
namespace JohnVanOrange\jvo;

require_once('smarty.php');

$api = new API();

$template = 'display.tpl';

$image_name = $route->get_data(0);

$tpl->assign('image', $api->call('image/get',array('image'=>$image_name)));

$tpl->assign('rand',md5(uniqid(rand(), true)));
$tpl->assign('report_types',$api->call('report/all'));

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");
$tpl->display($template);
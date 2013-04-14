<?php
namespace JohnVanOrange\jvo;

require_once('smarty.php');

$api = new API();

$template = 'image.tpl';

$image_name = $request[1];

$result = $api->call('image/get',array('image'=>$image_name));

$tpl->assign('image', $result['image_url']);

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");
header ('Access-Control-Allow-Origin: *');
$tpl->display($template);
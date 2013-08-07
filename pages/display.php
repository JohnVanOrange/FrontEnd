<?php
namespace JohnVanOrange\jvo;

require_once('twig.php');

$api = new API();

$image_name = $route->get_data(0);

$data = [
	'image'	=>	$api->call('image/get',array('image'=>$image_name)),
	'rand'	=>	md5(uniqid(rand(), true)),
	'report_types'	=>	$api->call('report/all')
];

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");

$template = $twig->loadTemplate('display.twig');
echo $template->render($data);
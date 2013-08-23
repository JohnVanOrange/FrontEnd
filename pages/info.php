<?php
namespace JohnVanOrange\jvo;

require_once('twig.php');

$api = new API();

$uid = $route->get_data(0);

$image = $api->call('image/get',array('image'=>$uid));

$data = [
 'image'	=>	$image
];

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");

$template = $twig->loadTemplate('info.twig');
echo $template->render($data);
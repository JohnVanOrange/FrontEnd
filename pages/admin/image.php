<?php
namespace JohnVanOrange\jvo;

require_once(__DIR__ . '/../twig.php');

$api = new API();

$uid = $route->get_data(1);

$image = $api->call('image/get',array('image'=>$uid));
$stats = $api->call('image/stats');
$stats['approved_percent'] = round(($stats['approved']/$stats['images']) * 100, 2);

$data = [
	'image'	=>	$image,
	'stats'	=>	$stats,
	'image_loc'	=>	WEB_ROOT.'media/'.$image['filename'],
	'rand'	=>	md5(uniqid(rand(), true))
];

require_once(__DIR__ . '/../common.php');
require_once('common.php');

$template = $twig->loadTemplate('admin_image.twig');
echo $template->render($data);
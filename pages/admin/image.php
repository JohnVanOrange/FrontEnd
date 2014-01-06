<?php
namespace JohnVanOrange\jvo;

$iface = new SiteInterface\Admin;

$uid = $route->get_data(1);

$image = $iface->api('image/get', ['image'=>$uid]);
$stats = $iface->api('image/stats');
$stats['approved_percent'] = round(($stats['approved']/$stats['images']) * 100, 2);

$iface->addData([
	'image'	=>	$image,
	'stats'	=>	$stats,
	'image_loc'	=>	WEB_ROOT.'media/'.$image['filename'],
	'rand'	=>	md5(uniqid(rand(), true))
]);

$iface->template('admin_image');
echo $iface->render();
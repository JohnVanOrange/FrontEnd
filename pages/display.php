<?php
namespace JohnVanOrange\jvo;

$iface = new SiteInterface\Standard;

$is_brazz = FALSE;
$request = $route->get_page();
if ($request == 'brazzify') $is_brazz = TRUE;

$image_name = $route->get_data(0);

$iface->addData([
	'image'	=>	$iface->api('image/get',
	  [
	   'image'	=>	$image_name,
	   'brazzify'	=>	$is_brazz
	  ]),
	'rand'	=>	md5(uniqid(rand(), true)),
	'ad' => $iface->api('ads/get')
]);

$iface->template('display');
echo $iface->render();
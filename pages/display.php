<?php
namespace JohnVanOrange\jvo;

$iface = new SiteInterface\Standard;

$is_brazz = FALSE;
$request = $route->get_page();
if ($request == 'brazzify') $is_brazz = TRUE;

$image_name = $route->get_data(0);

$image = $iface->api('image/get',
 [
  'image'	=>	$image_name,
  'brazzify'	=>	$is_brazz
 ]);

if (isset($image['merged_to'])) {
 header("HTTP/1.1 301 Moved Permanently");
 header("Location: /" . $image['merged_to']);
}

$iface->addData([
	'image'	=>	$image,
	'rand'	=>	md5(uniqid(rand(), true)),
	'ad' => $iface->api('ads/get')
]);

$iface->template('display');
echo $iface->render();
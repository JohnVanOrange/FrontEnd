<?php
namespace JohnVanOrange\jvo;

$iface = new SiteInterface\Standard;

$number = $route->get_data(0);

header("HTTP/1.0 ".$number);
$_SERVER['REDIRECT_STATUS'] = $number;

$iface->addData([
 'number'	=>	$number,
 'error_image'	=>	constant($number.'_IMAGE'),
 'rand'	=>	md5(uniqid(rand(), true))
]);

$iface->template('error');
echo $iface->render();
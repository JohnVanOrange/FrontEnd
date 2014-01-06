<?php
namespace JohnVanOrange\jvo;

$iface = new SiteInterface\Standard;

$uid = $route->get_data(0);

$image = $iface->api('image/get', ['image'=>$uid]);

$iface->addData([
 'image'	=>	$image
]);

$iface->template('info');
echo $iface->render();
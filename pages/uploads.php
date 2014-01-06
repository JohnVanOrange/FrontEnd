<?php
namespace JohnVanOrange\jvo;

$iface = new SiteInterface\Standard;

$username = $route->get_data(0);

$iface->addData([
	'images'	=>	$iface->api('user/uploaded', ['username'=>$username]),
	'title_text'	=>	_('Uploaded Images')
]);

$iface->template('thumbs');
echo $iface->render();
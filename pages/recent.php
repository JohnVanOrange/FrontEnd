<?php
namespace JohnVanOrange\jvo;

$iface = new SiteInterface\Standard;

$iface->addData([
	'images'	=>	$iface->api('image/recent'),
	'title_text'	=>	_('Recently Added Images')
]);

$iface->template('thumbs');
echo $iface->render();
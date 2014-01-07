<?php
namespace JohnVanOrange\jvo;

$iface = new SiteInterface\Standard;

$iface->addData([
	'images'	=>	$iface->api('image/recentLikes'),
	'title_text'	=>	_('Recently Liked Images')
]);

$iface->template('thumbs');
echo $iface->render();
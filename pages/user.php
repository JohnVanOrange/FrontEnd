<?php
namespace JohnVanOrange\jvo;

$iface = new SiteInterface\Standard;

$username = $route->get_data(0);

$iface->addData([
	'profile'	=>	$iface->api('user/get', ['value'=>$username, 'search_by'=>'username']),
	'title_text'	=>	_('Profile for') . ' ' . $username
]);

$iface->template('user');
echo $iface->render();
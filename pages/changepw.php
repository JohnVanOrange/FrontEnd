<?php
namespace JohnVanOrange\jvo;

$iface = new SiteInterface\Standard;

$iface->addData(['type' => 'sid']);
if (isset($_COOKIE['sid'])) $iface->addData(['auth' => $_COOKIE['sid']]);

if (isset($_GET['resetkey'])) {
	$iface->addData([
		'auth'	=>	$_GET['resetkey'],
		'type'	=>	'pwreset'
					 
	]);
}

$iface->template('changepw');
echo $iface->render();
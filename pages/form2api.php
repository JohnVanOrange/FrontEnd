<?php
namespace JohnVanOrange\jvo;

$iface = new SiteInterface\Standard;

$method = $_POST['method'];

$req = array_merge($_REQUEST, $_FILES);

$result = $iface->api($method, $req);

$iface->addData([
	'result'	=>	$result
]);

$iface->template('form2api');
echo $iface->render();
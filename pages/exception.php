<?php
namespace JohnVanOrange\jvo;

$iface = new SiteInterface\Standard;

$iface->addData([
 'code'	=>	$e->getCode(),
 'message'	=>	$e->getMessage()
]);

$iface->template('exception');
echo $iface->render();
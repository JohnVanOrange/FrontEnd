<?php
namespace JohnVanOrange\jvo;

$iface = new SiteInterface\Standard;

$iface->setContentType("Content-type: application/x-web-app-manifest+json; charset=UTF-8");

$iface->template('webapp');
echo $iface->render();
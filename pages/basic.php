<?php
namespace JohnVanOrange\jvo;

$iface = new SiteInterface\Standard;

$page = $route->get_page();

$iface->template($page);
echo $iface->render();
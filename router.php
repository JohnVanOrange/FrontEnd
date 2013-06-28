<?php
namespace JohnVanOrange\jvo;

require_once('vendor/autoload.php');
require_once('settings.inc');

$map = json_decode(file_get_contents('router_map.json'));

$route = new Route($_SERVER['REQUEST_URI'], $map, '/pages3/');

include($route->get());
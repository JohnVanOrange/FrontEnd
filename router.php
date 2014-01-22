<?php
namespace JohnVanOrange\jvo;

require_once('vendor/autoload.php');
require_once('settings.inc');

$data = json_decode(file_get_contents('router_data.json'));

$route = new Router($_SERVER['REQUEST_URI'], $data, '\JohnVanOrange\jvo\Controller\\');

$route->get();
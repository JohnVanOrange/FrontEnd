<?php
namespace JohnVanOrange\jvo;

$api = new API();

$class = $route->get_data(0);
$method = $route->get_data(1);

$req = array_merge($_REQUEST, $_FILES);

header ('Content-type: application/json; charset=UTF-8');
header ('Access-Control-Allow-Origin: *');

$result = $api->call($class.'/'.$method, $req, TRUE);

if (!is_array($result)) $result = ['response' => $result];
echo json_encode($result);
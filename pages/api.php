<?php
namespace JohnVanOrange\jvo;

$api = new API();

$class = $request[1];
$method = $request[2];

header ('Content-type: application/json; charset=UTF-8');
header ('Access-Control-Allow-Origin: *');

$result = $api->call($class.'/'.$method, $_REQUEST, TRUE);

if (!is_array($result)) $result = ['response' => $result];
echo json_encode($result);
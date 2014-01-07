<?php
namespace JohnVanOrange\jvo;

$api = new API;
//exceptions thrown from this won't be handled now
$image = $api->call('image/random');

header('Location: '. $image['page_url']);
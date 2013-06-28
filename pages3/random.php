<?php
namespace JohnVanOrange\jvo;

$api = new API();

$image = $api->call('image/random');

header('Location: '. $image['page_url']);
<?php
namespace JohnVanOrange\jvo;

$public = new PublicAPI;

$public->setClass($route->get_data(0));
$public->setMethod($route->get_data(1));
$public->setRequest(array_merge($_REQUEST, $_FILES));

$public->output();
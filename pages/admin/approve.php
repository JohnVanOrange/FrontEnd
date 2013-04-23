<?php
namespace JohnVanOrange\jvo;

$api = new API();

$url = parse_url(WEB_ROOT);
$tag = rtrim(str_replace($url['host'], '', $_SERVER['HTTP_HOST']),'.');

$image = $api->call('image/unapproved');

header('Location: '.$url['scheme'].'://'.$_SERVER['HTTP_HOST'].'/admin/image/'.$image['uid']);
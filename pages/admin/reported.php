<?php
$url = parse_url(WEB_ROOT);
$tag = rtrim(str_replace($url['host'], '', $_SERVER['HTTP_HOST']),'.');

$image = call('image/reported');

header('Location: '.$url['scheme'].'://'.$_SERVER['HTTP_HOST'].'/admin/image/'.$image['uid']);
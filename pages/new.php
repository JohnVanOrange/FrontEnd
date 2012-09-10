<?php
$url = parse_url(WEB_ROOT);
$tag = rtrim(str_replace($url['host'], '', $_SERVER['HTTP_HOST']),'.');

$image = call('image/random',array('new'=>TRUE));

header('Location: '.$url['scheme'].'://'.$_SERVER['HTTP_HOST'].'/n/'.$image['uid']);
?>
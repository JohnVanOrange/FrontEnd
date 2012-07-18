<?php
$url = parse_url(WEB_ROOT);
$tag = rtrim(str_replace($url['host'], '', $_SERVER['HTTP_HOST']),'.');

header('Location: '.$url['scheme'].'://'.$_SERVER['HTTP_HOST'].'/v/'.call('image/random',array('tag'=>$tag)));
?>

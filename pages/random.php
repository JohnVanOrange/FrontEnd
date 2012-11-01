<?php
if ($request[0] == 'm') setcookie('mobile', 'y',time()+60*60*24*365,'/');
if ($request[0] == 'f') setcookie('mobile', 'n',time()+60*60*24*365,'/');

$url = parse_url(WEB_ROOT);
$tag = rtrim(str_replace($url['host'], '', $_SERVER['HTTP_HOST']),'.');

$image = call('image/random',array('tag'=>$tag));

header('Location: '. $image['page_url']);
?>

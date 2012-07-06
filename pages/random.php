<?php
require_once('classes/image.class.php');

$image = new Image;

$url = parse_url(WEB_ROOT);
$tag = rtrim(str_replace($url['host'], '', $_SERVER['HTTP_HOST']),'.');

try {
 header('Location: '.$url['scheme'].'://'.$_SERVER['HTTP_HOST'].'/v/'.$image->random(array('tag'=>$tag)));
}
catch (exception $e) {
 page_exception_handler($e);
}
?>

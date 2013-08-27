<?php
require('twig.php');

$number = $route->get_data(0);

header("HTTP/1.0 ".$number);
$_SERVER['REDIRECT_STATUS'] = $number;

$data = [
 'number'	=>	$number,
 'error_image'	=>	constant($number.'_IMAGE'),
 'rand'	=>	md5(uniqid(rand(), true))
];

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");

$template = $twig->loadTemplate('error.twig');
echo $template->render($data);
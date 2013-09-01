<?php
namespace JohnVanOrange\jvo;

require('twig.php');

$api = new API();

$data = [
	'images'	=>	$api->call('image/recentLikes'),
	'title_text'	=>	'Recently Liked Images'
];

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");

$template = $twig->loadTemplate('thumbs.twig');
echo $template->render($data);
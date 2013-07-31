<?php
namespace JohnVanOrange\jvo;

require('twig.php');

$api = new API();

$tag_name = $route->get_data(0);

$page_tag = $api->call('tag/get',array('value'=>$tag_name,'search_by'=>'basename'));

$data = [
	'images'	=>	$api->call('tag/all',array('tag'=>$tag_name)),
	'title_text'	=>	'Images tagged ' . $page_tag[0]['name']
];

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");

$template = $twig->loadTemplate('thumbs.twig');
echo $template->render($data);
?>
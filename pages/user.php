<?php
namespace JohnVanOrange\jvo;

require('twig.php');

$api = new API();

$username = $route->get_data(0);

$data = [
	'profile'	=>	$api->call('user/get',array('value'=>$username,'search_by'=>'username')),
	'title_text'	=>	_('Profile for') . ' ' . $username
];

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");

$template = $twig->loadTemplate('user.twig');
echo $template->render($data);
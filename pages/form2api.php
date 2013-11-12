<?php
namespace JohnVanOrange\jvo;

require_once('twig.php');

$api = new API();

$method = $_POST['method'];

$req = array_merge($_REQUEST, $_FILES);

$result = $api->call($method, $req);

$data = [
	'result'	=>	$result
];

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");

$template = $twig->loadTemplate('form2api.twig');
echo $template->render($data);
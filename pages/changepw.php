<?php
namespace JohnVanOrange\jvo;

require_once('twig.php');

$data['type'] = 'sid';
if (isset($_COOKIE['sid'])) $data['auth'] = $_COOKIE['sid'];

if (isset($_GET['resetkey'])) {
	$data['auth'] = $_GET['resetkey'];
	$data['type'] = 'pwreset';
}

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");

$template = $twig->loadTemplate('changepw.twig');
echo $template->render($data);
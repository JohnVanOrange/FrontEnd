<?php
require('twig.php');

$data = [
 'code'	=>	$e->getCode(),
 'message'	=>	$e->getMessage()
];

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");

$template = $twig->loadTemplate('exception.twig');
echo $template->render($data);
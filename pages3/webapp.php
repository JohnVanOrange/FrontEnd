<?php
require_once('twig.php');

require_once('common.php');

header("Content-type: application/x-web-app-manifest+json; charset=UTF-8");

$template = $twig->loadTemplate('webapp.twig');
echo $template->render($data);
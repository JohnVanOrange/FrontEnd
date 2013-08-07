<?php
require_once('twig.php');

$page = $route->get_page();

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");

$template = $twig->loadTemplate($page . '.twig');
echo $template->render($data);
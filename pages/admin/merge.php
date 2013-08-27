<?php
namespace JohnVanOrange\jvo;

require_once(__DIR__ . '/../twig.php');

require_once(__DIR__ . '/../common.php');
require_once('common.php');

$template = $twig->loadTemplate('admin_merge.twig');
echo $template->render($data);
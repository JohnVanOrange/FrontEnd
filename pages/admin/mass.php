<?php
namespace JohnVanOrange\jvo;

require_once('smarty.php');

$template = 'mass.tpl';

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");
$tpl->display($template);
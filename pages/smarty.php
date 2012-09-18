<?php
switch ($_COOKIE['mobile']) {
 case 'y':
  $temptype = 'mobile';
 break;
 case 'n':
 default:
  $temptype = 'ui2';
 break;
}

//if ($_GET['type']) $temptype = $_GET['type']; //this shouldn't be left in production code

define('SMARTY_DIR',ROOT_DIR.'/smarty/libs/');
define('TEMPLATE_DIR',ROOT_DIR.'/templates/'.$temptype);

require_once(SMARTY_DIR.'Smarty.class.php');
$tpl = new Smarty;

$tpl->setTemplateDir(TEMPLATE_DIR);
$tpl->setCacheDir(SMARTY_DIR.'../cache/');
$tpl->setCompileDir(SMARTY_DIR.'../templates_c/');
?>
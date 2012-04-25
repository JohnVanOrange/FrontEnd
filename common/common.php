<?php
define('SMARTY_DIR',ROOT_DIR.'/smarty/libs/');
define('TEMPLATE_DIR',ROOT_DIR.'/templates');

require_once(SMARTY_DIR.'Smarty.class.php');
$tpl = new Smarty;

require_once(ROOT_DIR.'/common/db.php');
$db = new DB('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);

$tpl->setTemplateDir(TEMPLATE_DIR);
$tpl->setCacheDir(SMARTY_DIR.'../cache/');
$tpl->setCompileDir(SMARTY_DIR.'../templates_c/');
?>

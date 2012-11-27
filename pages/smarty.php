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

define('TEMPLATE_DIR',ROOT_DIR.'/templates/'.$temptype);

$tpl = new Smarty;

$tpl->setTemplateDir(TEMPLATE_DIR);
?>

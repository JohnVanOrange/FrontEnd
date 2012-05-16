<?php

$tpl->assign('ga',GOOGLE_ANALYTICS);
$tpl->assign('site_name',SITE_NAME);
$tpl->assign('web_root',WEB_ROOT);

if ($_COOKIE['theme']) {
 $tpl->assign('theme',$_COOKIE['theme']);
}
else {
 $tpl->assign('theme','light');
}
?>

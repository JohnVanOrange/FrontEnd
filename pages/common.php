<?php

$tpl->assign('ga',GOOGLE_ANALYTICS);
$tpl->assign('site_name',SITE_NAME);
$tpl->assign('web_root',WEB_ROOT);
$tpl->assign('show_scorn',SHOW_SCORN);
$tpl->assign('show_social',SHOW_SOCIAL);
$tpl->assign('favicon',FAVICON);

if ($_COOKIE['theme']) {
 $tpl->assign('theme',$_COOKIE['theme']);
}
else {
 $tpl->assign('theme','dark');
}
?>

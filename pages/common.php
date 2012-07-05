<?php
require_once('common/user.class.php');

$user = new User;

$tpl->assign('ga',GOOGLE_ANALYTICS);
$tpl->assign('site_name',SITE_NAME);
$tpl->assign('web_root',WEB_ROOT);
$tpl->assign('show_scorn',SHOW_SCORN);
$tpl->assign('show_social',SHOW_SOCIAL);
$tpl->assign('show_brazz',SHOW_BRAZZ);
$tpl->assign('favicon',FAVICON);
$tpl->assign('user',$user->current());

if ($_COOKIE['theme']) {
 $tpl->assign('theme',$_COOKIE['theme']);
}
else {
 $tpl->assign('theme','dark');
}
?>

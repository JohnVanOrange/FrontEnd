<?php
require_once('classes/user.class.php');
require_once('classes/theme.class.php');

$user = new User;
$theme = new Theme;

$tpl->assign('ga',GOOGLE_ANALYTICS);
$tpl->assign('site_name',SITE_NAME);
$tpl->assign('web_root',WEB_ROOT);
$tpl->assign('show_scorn',SHOW_SCORN);
$tpl->assign('show_social',SHOW_SOCIAL);
$tpl->assign('show_brazz',SHOW_BRAZZ);
$tpl->assign('favicon',FAVICON);
$tpl->assign('user',$user->current());
$tpl->assign('theme', $theme->get());

?>

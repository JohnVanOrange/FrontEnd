<?php
$user = call('user/current');

$tpl->assign('ga',GOOGLE_ANALYTICS);
$tpl->assign('site_name',SITE_NAME);
$tpl->assign('web_root',WEB_ROOT);
$tpl->assign('show_scorn',SHOW_SCORN);
$tpl->assign('show_social',SHOW_SOCIAL);
$tpl->assign('show_brazz',SHOW_BRAZZ);
$tpl->assign('favicon',FAVICON);
if (defined('APP_LINK')) $tpl->assign('app_link',APP_LINK);
$tpl->assign('user', $user);
if ($user['type'] > 1) $tpl->assign('is_admin', TRUE);
$tpl->assign('site_theme', THEME);
$tpl->assign('current_url', 'http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);
?>
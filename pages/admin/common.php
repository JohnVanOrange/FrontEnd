<?php
namespace JohnVanOrange\jvo;

$api = new API();

$current = $api->call('user/current');
if ($current['type'] < 2) throw new Exception('Must be an admin to access', 401);
 
$tpl->assign('site_name',SITE_NAME);
$tpl->assign('web_root',WEB_ROOT);
$tpl->assign('icon_set',ICON_SET);
$tpl->assign('site_theme', THEME);
$tpl->assign('user', $api->call('user/current'));
<?php
namespace JohnVanOrange\jvo;

if (!isset($api)) {
	$api = new API();
}
	
$user = $api->call('user/current');
if (!isset($user['id'])) $user['id'] = 0;

$data['user'] = $user;
$data['ga'] = GOOGLE_ANALYTICS;
$data['site_name'] = SITE_NAME;
$data['web_root'] = WEB_ROOT;
$data['hostname'] = parse_url(WEB_ROOT)['host'];
$data['show_social'] = SHOW_SOCIAL;
$data['icon_set'] = ICON_SET;
$data['site_theme'] = THEME;
$data['current_url'] = 'http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
$data['browser'] = \Browser\Browser::getBrowser();
$data['locale'] = $locale;
if (defined('APP_LINK')) $data['app_link'] = APP_LINK;
if (defined('SHOW_JVON')) $data['show_jvon'] = SHOW_JVON;
if (defined('SHOW_BRAZZ')) $data['show_brazz'] = SHOW_BRAZZ;
if (defined('FB_APP_ID')) $data['fb_app_id'] = FB_APP_ID;
if ($api->call('user/isAdmin')) $data['is_admin'] = TRUE;
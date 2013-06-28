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
$data['browser'] = browser_info();
if (defined('APP_LINK')) $data['app_link'] = APP_LINK;
if (defined('SHOW_JVON')) $data['show_jvon'] = SHOW_JVON;
if (defined('FB_APP_ID')) $data['fb_app_id'] = FB_APP_ID;
if ($api->call('user/isAdmin')) $data['is_admin'] = TRUE;

function browser_info($agent=null) {
  // Declare known browsers to look for
  $known = array('chrome', 'msie', 'firefox', 'safari', 'webkit', 'opera', 'netscape', 'konqueror', 'gecko');

  // Clean up agent and build regex that matches phrases for known browsers
  // (e.g. "Firefox/2.0" or "MSIE 6.0" (This only matches the major and minor
  // version numbers.  E.g. "2.0.0.6" is parsed as simply "2.0"
  $agent = strtolower($agent ? $agent : $_SERVER['HTTP_USER_AGENT']);
  $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9]+(?:\.[0-9]+)?)#';

  // Find all phrases (or return empty array if none found)
  if (!preg_match_all($pattern, $agent, $matches)) return array();

  // Since some UAs have more than one phrase (e.g Firefox has a Gecko phrase,
  // Opera 7,8 have a MSIE phrase), use the last one found (the right-most one
  // in the UA).  That's usually the most correct.
  $i = count($matches['browser'])-1;
  return array('name' => $matches['browser'][$i] , 'version' => $matches['version'][$i]);
}
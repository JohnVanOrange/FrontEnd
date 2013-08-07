<?php
namespace JohnVanOrange\jvo;

$api = new API();

$user = $api->call('user/current');
if (!isset($user['id'])) $user['id'] = 0;

$tpl->assign('ga',GOOGLE_ANALYTICS);
$tpl->assign('site_name',SITE_NAME);
$tpl->assign('web_root',WEB_ROOT);
$tpl->assign('hostname', parse_url(WEB_ROOT)['host']);
$tpl->assign('show_scorn',SHOW_SCORN);
$tpl->assign('show_social',SHOW_SOCIAL);
$tpl->assign('icon_set',ICON_SET);
if (defined('APP_LINK')) $tpl->assign('app_link',APP_LINK);
if (defined('SHOW_JVON')) $tpl->assign('show_jvon',SHOW_JVON);
if (defined('FB_APP_ID')) $tpl->assign('fb_app_id',FB_APP_ID);
$tpl->assign('user', $user);
if ($api->call('user/isAdmin')) $tpl->assign('is_admin', TRUE);
$tpl->assign('site_theme', THEME);
$tpl->assign('current_url', 'http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);

$tpl->assign('browser', browser_info());

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
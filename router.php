<?php
namespace JohnVanOrange\jvo;

require_once('vendor/autoload.php');
require_once('settings.inc');

$locale = \Browser\Language::getLanguageLocale('_');

putenv('LC_ALL=' . $locale);
setlocale(LC_ALL, $locale);
bindtextdomain('messages', ROOT_DIR.'/locale');
bind_textdomain_codeset('messages', 'UTF-8');

$map = json_decode(file_get_contents('router_map.json'));

$route = new Route($_SERVER['REQUEST_URI'], $map);

include($route->get());
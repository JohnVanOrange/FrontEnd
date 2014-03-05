<?php
namespace JohnVanOrange\jvo;

require_once('../../vendor/autoload.php');
require_once('../../settings.inc');

$setting = new Setting;

echo "Admin session ID: ";
$handle = fopen ("php://stdin","r");
$sid = trim(fgets($handle));

$convert = [
 'WEB_ROOT',
 'SITE_NAME',
 'BRANCH',
 'THEME',
 'ICON_SET',
 'GOOGLE_ANALYTICS',
 'APP_LINK',
 'SHOW_BRAZZ',
 'SHOW_SOCIAL',
 'SHOW_JVON',
 'DISABLE_UPLOAD',
 'FB_APP_ID',
 'AMAZON_AFF',
 '403_IMAGE',
 '404_IMAGE'
];

foreach ($convert as $name) {
 $setting->update(strtolower($name), constant($name), $sid);
 echo "Added " . $name . " to the database.\n";
}
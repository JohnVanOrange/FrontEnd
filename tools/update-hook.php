<?php
require_once(__DIR__.'/../vendor/autoload.php');
require_once(__DIR__.'/../settings.inc');

$setting = new JohnVanOrange\jvo\Setting;
$branch = $setting->get('branch');
$site_name = $setting->get('site_name');

$payload = json_decode($_POST['payload']);

if ($payload->ref == 'refs/heads/'. $branch) {
  echo '<pre>';
  $results = shell_exec('sudo -u ' . SERVER_USER . ' env PATH=$PATH ./fullupdate ' . $branch . ' 2>&1');
  echo $results;
  mail(
   ADMIN_EMAIL,
   $branch . ' branch deployed on '. $site_name,
   'User: ' . SERVER_USER . "\n" .
   'Branch: ' . $branch . "\n" .
   $results
  );
}

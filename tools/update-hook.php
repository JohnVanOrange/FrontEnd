<?php
require_once(__DIR__.'/../settings.inc');

$payload = json_decode($_POST['payload']);

if ($payload->ref == 'refs/heads/'. BRANCH) {
  echo '<pre>';
  $results = shell_exec('sudo -u ' . SERVER_USER . ' env PATH=$PATH ./fullupdate ' . BRANCH . ' 2>&1');
  echo $results;
  mail(
   ADMIN_EMAIL,
   BRANCH . ' branch deployed on '. SITE_NAME,
   'User: ' . SERVER_USER . "\n" .
   'Branch: ' . BRANCH . "\n" .
   $results
  );
}
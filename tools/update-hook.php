<?php
require_once(__DIR__.'/../settings.inc');

$payload = json_decode($_POST['payload']);

if ($payload->ref == 'refs/heads/'. BRANCH) {
  echo '<pre>';
  $results = shell_exec('sudo -u ' . SERVER_USER . ' env PATH=$PATH ../fullupdate ' . BRANCH);
  echo $results;
  mail(
   ADMIN_EMAIL,
   BRANCH . ' branch deployed on '. SITE_NAME,
   'User: ' . SERVER_USER . "\n" .
   'Branch: ' . BRANCH . "\n" .
   $results
  );
}
else {
 echo "An error has occurred.\n\n";
 print_r($_POST);
 mail(
  ADMIN_EMAIL,
  BRANCH . ' branch did not deploy on '. SITE_NAME,
 $payload->ref
 );
}
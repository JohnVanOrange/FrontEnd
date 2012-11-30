<?php
require_once('vendor/autoload.php');
require_once('settings.inc');
require_once('common/exceptions.php');

$full_request = $_SERVER['REQUEST_URI'];
$request = explode('?',$full_request);
$request = explode('/',trim($request[0],'/'));

$map = json_decode(file_get_contents('router_map.json'));

switch($request[0]) {
 case '':
  include(ROOT_DIR.'/pages/random.php');
 break;
 case 'admin':
  include(ROOT_DIR.'/pages/admin/'.$request[1].'.php');
 break;
 default:
  $location = ROOT_DIR.'/pages/'.$map->{$request[0]}.'.php';
  if(file_exists($location)) {
   include($location);
  }
  else {
   include(ROOT_DIR.'/pages/random.php');
  } 
 break;
}
?>

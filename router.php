<?php
require_once('settings.inc');
require_once('common/exceptions.php');

$full_request = $_SERVER['REQUEST_URI'];
$request = explode('/',trim($full_request,'/'));

switch($request[0]) {

 case 'random':
  include(ROOT_DIR.'/pages/random.php');
 break;
  
 case 'brazzify':
  $brazzify = TRUE;
 
 case 'display':
  $image = $request[1];
  include(ROOT_DIR.'/pages/display.php');
 break;

 case 'api':
  $method = $request[1];
  include(ROOT_DIR.'/pages/api.php');
 break;

 case 'error':
  $number = $request[1];
  include(ROOT_DIR.'/pages/error.php');
 break;

 case 'tos':
  include(ROOT_DIR.'/pages/tos.php');
 break;

 default:
  include(ROOT_DIR.'/pages/random.php');
 break;
}

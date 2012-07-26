<?php
require_once('settings.inc');
require_once('common/exceptions.php');

$full_request = $_SERVER['REQUEST_URI'];
$request = explode('?',$full_request);
$request = explode('/',trim($request[0],'/'));

switch($request[0]) {

 case 'm':
  setcookie('mobile', 'y',time()+60*60*24*365,'/');
 case 'random':
  include(ROOT_DIR.'/pages/random.php');
 break;

 case 'f':
  setcookie('mobile', 'n',time()+60*60*24*365,'/');
  include(ROOT_DIR.'/pages/random.php');
 break;
 
 case 'b':
 case 'brazzify':
  $brazzify = TRUE;
 case 'v':
 case 'display':
  $image_name = $request[1];
  include(ROOT_DIR.'/pages/display.php');
 break;

 case 'i':
  $image_name = $request[1];
  include(ROOT_DIR.'/pages/image.php');
 break;

 case 'api':
  $class = $request[1];
  $method = $request[2];
  include(ROOT_DIR.'/pages/api.php');
 break;

 case 's':
  include(ROOT_DIR.'/pages/saved.php');
 break;

 case 't':
  $tag_name = $request[1];
  include(ROOT_DIR.'/pages/tag.php');
 break;

 case 'upload':
  include(ROOT_DIR.'/pages/upload.php');
 break;

 case 'error':
  $number = $request[1];
  include(ROOT_DIR.'/pages/error.php');
 break;

 case 'tos':
  include(ROOT_DIR.'/pages/tos.php');
 break;
 
 case 'bot':
  include(ROOT_DIR.'/pages/bot.php');
 break;

 default:
  include(ROOT_DIR.'/pages/random.php');
 break;
}

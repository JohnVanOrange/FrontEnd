<?php

function page_exception_handler($e) {
 switch($e->getCode()) {
  case 403:
  case 404:
   $request[1] = $e->getCode();
   include(ROOT_DIR.'/pages/error.php');
   die();
   break;
   default:
    $message = 'An error occured for site '. SITE_NAME . ".\n\n";
    $message .= "Error:\n";
    $message .= $e->getMessage()."\n\n";
    $message .= "Code:\n";
    $message .= $e->getCode()."\n\n";
    $message .= "Page requested:\n";
    $message .= $_SERVER['REQUEST_URI']."\n\n";    
    $message .= "IP:\n";
    $message .= $_SERVER['REMOTE_ADDR'];
    mail(
     ADMIN_EMAIL,
     'Error Occured for '. SITE_NAME,
     $message
    );
    include(ROOT_DIR.'/pages/exception.php');
    die();
   break;
 }
}

function js_exception_handler($e) {
 if ($e->getCode() == 403 || $e->getCode() == 404) {
  header("HTTP/1.0 ".$e->getCode());
  $_SERVER['REDIRECT_STATUS'] = $e->getCode();
 }
 echo json_encode(array(
  'error' => $e->getCode(),
  'message' => $e->getMessage()
 ));
 die();
}
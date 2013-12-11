<?php
namespace JohnVanOrange\jvo;

class Exception {

 public function __construct() {
 }
 
 /*
  * Page exception handler
  *
  * Handles exceptions that will be output to the page
  *
  * @param \Exception $e Thrown exception
  */
 
 public function page($e) {
    switch($e->getCode()) {
     case 403:
     case 404:
      $route = new Route();
      $route->set_data(0, $e->getCode());
      include(ROOT_DIR.'/pages/error.php');
      die();
      break;
      default:
       $body = 'An error occured for site '. SITE_NAME . ".\n\n";
       $body .= "Error:\n";
       $body .= $e->getMessage()."\n\n";
       $body .= "Code:\n";
       $body .= $e->getCode()."\n\n";
       $body .= "Page requested:\n";
       $body .= $_SERVER['REQUEST_URI']."\n\n";    
       $body .= "IP:\n";
       $body .= $_SERVER['REMOTE_ADDR'];
       $message = new Mail();
       $message->sendAdminMessage('Error Occured for '. SITE_NAME, $body);
       include(ROOT_DIR.'/pages/exception.php');
       die();
      break;
    }
 }
 
 /*
  * JS exception handler
  *
  * Handles exceptions that will be output as JSON responses
  *
  * @param \Exception $e Thrown exception
  */
  
 public function js($e) {
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
 
}
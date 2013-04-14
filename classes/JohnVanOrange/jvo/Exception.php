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
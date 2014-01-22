<?php
namespace JohnVanOrange\jvo\Controller;

class NotFound extends Standard {
 
 public function process() {
  
  $this->setTemplate('error');
	
  header("HTTP/1.0 404");
  $_SERVER['REDIRECT_STATUS'] = 404;
  $this->addData([
   'number'	=>	404,
   'error_image'	=>	constant('404_IMAGE'),
   'rand'	=>	md5(uniqid(rand(), true))
  ]);
	
 }
 
}
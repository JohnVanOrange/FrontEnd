<?php
namespace JohnVanOrange\jvo\Controller;

class NotFound extends Standard {
 
 public function process() {
  
  $this->setTemplate('error');
	
  header("HTTP/1.0 404");
  $this->addData([
   'number'	=>	404,
   'error_image'	=>	$this->api('setting/get', ['name' => '404_image']),
   'rand'	=>	md5(uniqid(rand(), true))
  ]);
	
 }
 
}
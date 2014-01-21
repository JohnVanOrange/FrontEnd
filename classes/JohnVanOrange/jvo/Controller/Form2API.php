<?php
namespace JohnVanOrange\jvo\Controller;

class Form2API extends Standard {
 
 public function process() {
  
  $this->setTemplate('form2api');
	
	$method = $_POST['method'];
	
	$req = array_merge($_REQUEST, $_FILES);
	
	$result = $this->api($method, $req);
	
	$this->addData([
		'result'	=>	$result
	]);
	
 }
 
}
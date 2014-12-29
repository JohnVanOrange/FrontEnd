<?php
namespace JohnVanOrange\jvo\Controller;

class Form2API extends Standard {
 
 public function process() {
  
  $this->setTemplate('form2api');
	
	if (isset($_POST['method'])) {
	 $method = $_POST['method'];
	}
	
	if (!isset($method)) {
	 header('Location: /');
	 exit();
	}
	
	$req = array_merge($_REQUEST, $_FILES);
	
	$result = $this->api($method, $req);
	
	$this->addData([
		'result'	=>	$result
	]);
	
 }
 
}
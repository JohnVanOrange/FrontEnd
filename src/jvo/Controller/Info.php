<?php
namespace JohnVanOrange\jvo\Controller;

class Info extends Standard {
 
 public function process() {
  
  $this->setTemplate('info');
	
	$uid = $this->route->get_data(1);
	
	$image = $this->api('image/get', ['image'=>$uid]);
	
	$this->addData([
	 'image'	=>	$image
	]);
	
 }
 
}
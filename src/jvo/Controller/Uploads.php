<?php
namespace JohnVanOrange\jvo\Controller;

class Uploads extends Standard {
 
 public function process() {
  
  $this->setTemplate('thumbs');
	
	$username = $this->route->get_data(1);
	
	$this->addData([
		'images'	=>	$this->api('user/uploaded', ['username'=>$username]),
		'title_text'	=>	_('Uploaded Images')
	]);
	
 }
 
}
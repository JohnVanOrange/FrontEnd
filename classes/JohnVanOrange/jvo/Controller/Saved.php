<?php
namespace JohnVanOrange\jvo\Controller;

class Saved extends Standard {
 
 public function process() {
  
  $this->setTemplate('thumbs');
	
	$username = $this->route->get_data(0);
	
	$this->addData([
		'images'	=>	$this->api('user/saved', ['username'=>$username]),
		'title_text'	=>	_('Saved Images')
	]);
	
 }
 
}
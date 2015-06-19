<?php
namespace JohnVanOrange\jvo\Controller;

class User extends Standard {
 
 public function process() {
  
  $this->setTemplate('user');
	
	$username = $this->route->get_data(1);
	
	$this->addData([
		'profile'	=>	$this->api('user/get', ['value'=>$username, 'search_by'=>'username']),
		'title_text'	=>	'Profile for' . ' ' . $username
	]);
	
 }
 
}
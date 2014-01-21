<?php
namespace JohnVanOrange\jvo\Controller;

class User extends Standard {
 
 public function process() {
  
  $this->setTemplate('user');
	
	$username = $this->route->get_data(0);
	
	$this->addData([
		'profile'	=>	$this->api('user/get', ['value'=>$username, 'search_by'=>'username']),
		'title_text'	=>	_('Profile for') . ' ' . $username
	]);
	
 }
 
}
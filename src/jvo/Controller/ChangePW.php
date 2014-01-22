<?php
namespace JohnVanOrange\jvo\Controller;

class ChangePW extends Standard {
 
 public function process() {
  
  $this->setTemplate('changepw');
  
  $this->addData(['type' => 'sid']);
  if (isset($_COOKIE['sid'])) $this->addData(['auth' => $_COOKIE['sid']]);

  if (isset($_GET['resetkey'])) {
   $this->addData([
    'auth'	=>	$_GET['resetkey'],
	'type'	=>	'pwreset'
   ]);
  }
  
 }
 
}
<?php
namespace JohnVanOrange\jvo\Controller;

class Cloud extends Standard {
 
 public function process() {
  
  $this->setTemplate('cloud');
  
  $tc = new Components\TagCloud;

  $this->addData([
		'tags'	=>	$tc->getData(),
		'title_text'	=>	'Tag Cloud'
  ]);

 }
 
}
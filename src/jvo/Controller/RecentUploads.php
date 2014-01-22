<?php
namespace JohnVanOrange\jvo\Controller;

class RecentUploads extends Standard {
 
 public function process() {
  
  $this->setTemplate('thumbs');
	
  $this->addData([
      'images'	=>	$this->api('image/recent'),
      'title_text'	=>	_('Recently Added Images')
  ]);
	
 }
 
}
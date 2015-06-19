<?php
namespace JohnVanOrange\jvo\Controller;

class RecentUploads extends Standard {
 
 public function process() {
  
  $this->setTemplate('thumbs');
	
  $this->addData([
      'images'	=>	$this->api('image/recent', ['count' => 50]),
      'title_text'	=>	'Recently Added Images'
  ]);
	
 }
 
}
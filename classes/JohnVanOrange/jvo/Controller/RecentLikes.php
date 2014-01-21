<?php
namespace JohnVanOrange\jvo\Controller;

class RecentLikes extends Standard {
 
 public function process() {
  
  $this->setTemplate('thumbs');
	
  $this->addData([
      'images'	=>	$this->api('image/recentLikes'),
      'title_text'	=>	_('Recently Liked Images')
  ]);
	
 }
 
}
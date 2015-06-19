<?php
namespace JohnVanOrange\jvo\Controller;

class RecentLikes extends Standard {
 
 public function process() {
  
  $this->setTemplate('thumbs');
	
  $this->addData([
      'images'	=>	$this->api('image/recentLikes', ['count' => 50]),
      'title_text'	=>	'Recently Liked Images'
  ]);
	
 }
 
}
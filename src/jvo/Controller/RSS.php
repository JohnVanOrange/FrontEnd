<?php
namespace JohnVanOrange\jvo\Controller;

class RSS extends Standard {
 
 public function process() {
	
  $this->setTemplate('rss');
  
  $this->setContentType("Content-type: application/rss+xml; charset=UTF-8");
	
  //should eventually add options for approved/nsfw filters and count
  $this->addData(
  [
   'images'	=>	$this->api('image/random', [NULL, 'count' => 20])
  ]);
	
 }
 
}
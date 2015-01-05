<?php
namespace JohnVanOrange\jvo\Controller;

class RSS extends Standard {
 
 public function process() {
	
  $this->setTemplate('rss');
  
  $this->setContentType("Content-type: application/rss+xml; charset=UTF-8");
	
	$images = [];
	
	//should eventually add options for approved/nsfw filters, and maybe a count
	for ($i = 0; $i < 20; $i++) {
	 $images[] = $this->api('image/random');
	}
  //Also need a way to make sure all entries are unique
	
  $this->addData(
  [
   'images'	=>	$images
  ]);
	
 }
 
}
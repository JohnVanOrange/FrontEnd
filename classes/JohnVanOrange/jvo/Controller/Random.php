<?php
namespace JohnVanOrange\jvo\Controller;

class Random extends Base {
 
 public function process() {
	
	$api = new \JohnVanOrange\jvo\API;
	//exceptions thrown from this won't be handled now
	$image = $api->call('image/random');
	
	header('Location: '. $image['page_url']);
	
 }
 
}
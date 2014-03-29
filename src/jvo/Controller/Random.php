<?php
namespace JohnVanOrange\jvo\Controller;

class Random extends Base {
 
 public function process() {
	
	$api = new \JohnVanOrange\jvo\API;
	
	$options = [];
	if (isset($_COOKIE['filter'])) {
	 $options['filter'] = $_COOKIE['filter'];
	}
	
	//exceptions thrown from this won't be handled now
	$image = $api->call('image/random', $options);
	
	header('Location: '. $image['page_url']);
	
 }
 
}
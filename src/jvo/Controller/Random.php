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
	try {
	 $image = $api->call('image/random', $options);
	}
	catch(\Exception $e) {
	 $interface = new \JohnVanOrange\jvo\SiteInterface\Standard;
	 $interface->exceptionHandler($e);
	}
	
	header('Location: '. $image['page_url']);
	
 }
 
}
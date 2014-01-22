<?php
namespace JohnVanOrange\jvo\Controller;

class Display extends Standard {
 
 public function process() {
  
  $this->setTemplate('display');
	
  $is_brazz = FALSE;
	$request = $this->route->get_page();
	if ($request == 'brazzify') $is_brazz = TRUE;
	
	$image_name = $this->route->get_data(1);
	
	$image = $this->api('image/get',
	 [
		'image'	=>	$image_name,
		'brazzify'	=>	$is_brazz
	 ]);
	
	if (isset($image['merged_to'])) {
	 header("HTTP/1.1 301 Moved Permanently");
	 header("Location: /" . $image['merged_to']);
	}
	
	$this->addData([
		'image'	=>	$image,
		'rand'	=>	md5(uniqid(rand(), true)),
		'ad' => $this->api('ads/get')
	]);
	
 }
 
}
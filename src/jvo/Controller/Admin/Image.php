<?php
namespace JohnVanOrange\jvo\Controller\Admin;

class Image extends Admin {
 
 public function process() {
  
  $this->setTemplate('admin_image');
  
  $uid = $this->route->get_data(2);

  $image = $this->api('image/get', ['image'=>$uid]);
  $stats = $this->api('image/stats');
  $stats['approved_percent'] = round(($stats['approved']/$stats['images']) * 100, 2);
  
  $this->addData([
      'image'	=>	$image,
      'stats'	=>	$stats,
      'image_loc'	=> $image['media']['primary']['url'],
      'rand'	=>	md5(uniqid(rand(), true))
  ]);
  
 }
 
}
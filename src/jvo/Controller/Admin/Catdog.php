<?php
namespace JohnVanOrange\jvo\Controller\Admin;

class Catdog extends Admin {
 
 public function process() {
  
  $this->setTemplate('admin_catdog');

  $image = $this->api('image/random');
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
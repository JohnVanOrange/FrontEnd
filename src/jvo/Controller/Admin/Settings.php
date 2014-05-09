<?php
namespace JohnVanOrange\jvo\Controller\Admin;

class Settings extends Admin {
 
 public function process() {
  
  $this->setTemplate('admin_settings');
  

  $all_settings = $this->api('setting/all');
  foreach ($all_settings as $setting) {
      $settings[$setting] = $this->api('setting/get', ['name' => $setting]);
  }
  
  $this->addData([
      'settings'    =>    $settings
  ]);
  
 }
 
}
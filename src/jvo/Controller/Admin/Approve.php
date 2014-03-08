<?php
namespace JohnVanOrange\jvo\Controller\Admin;

class Approve extends Admin {
 
 public function process() {
  
  $url = parse_url($this->api('setting/get', ['name' => 'web_root']));
  
  $image = $this->api('image/unapproved');
  
  header('Location: '.$url['scheme'].'://'.$_SERVER['HTTP_HOST'].'/admin/image/'.$image['uid']);
  
 }
 
}
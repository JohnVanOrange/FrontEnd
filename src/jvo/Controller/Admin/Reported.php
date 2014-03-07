<?php
namespace JohnVanOrange\jvo\Controller\Admin;

class Reported extends Admin {
 
 public function process() {
  
  $url = parse_url($this->api('setting/get', ['name' => 'web_root']));
  $tag = rtrim(str_replace($url['host'], '', $_SERVER['HTTP_HOST']),'.');
  
  $image = $this->api('image/reported');
  
  header('Location: '.$url['scheme'].'://'.$_SERVER['HTTP_HOST'].'/admin/image/'.$image['uid']);
  
 }
 
}
<?php
namespace JohnVanOrange\jvo\Controller\Admin;

class Approve extends Admin {
 
 public function process() {
  
  $url = parse_url(WEB_ROOT);
  $tag = rtrim(str_replace($url['host'], '', $_SERVER['HTTP_HOST']),'.');
  
  $image = $this->api('image/unapproved');
  
  header('Location: '.$url['scheme'].'://'.$_SERVER['HTTP_HOST'].'/admin/image/'.$image['uid']);
  
 }
 
}
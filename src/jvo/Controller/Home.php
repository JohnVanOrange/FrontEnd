<?php
namespace JohnVanOrange\jvo\Controller;

class Home extends Standard {
 
 public function process() {
  
  $this->setTemplate('home');
    
  $this->addData(
  [
   'rand'    =>    md5(uniqid(rand(), true)),
   'recent_likes'   =>  $this->api('image/recentLikes', ['count' => 8]),
   'recent'   =>  $this->api('image/recent', ['count' => 8])
  ]);
    
 }
 
}
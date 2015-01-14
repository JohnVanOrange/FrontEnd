<?php
namespace JohnVanOrange\jvo\Controller;

class Home extends Standard {
 
 public function process() {
  
  $this->setTemplate('home');
  
  //$tc = new Components\TagCloud;
    
  $this->addData(
  [
   'rand'    =>    md5(uniqid(rand(), true)),
   'recent_likes'   =>  $this->api('image/recentLikes', ['count' => 24]),
   'recent'   =>  $this->api('image/recent', ['count' => 24])
   //'tags' => $tc->getData()
  ]);
    
 }
 
}
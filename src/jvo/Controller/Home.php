<?php
namespace JohnVanOrange\jvo\Controller;

class Home extends Standard {
 
 public function process() {
  
  $this->setTemplate('home');
  
  //$tc = new Components\TagCloud;
    
  $this->addData(
  [
   'rand'    =>    md5(uniqid(rand(), true)),
   //'tags' => $tc->getData()
  ]);
    
 }
 
}
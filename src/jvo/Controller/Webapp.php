<?php
namespace JohnVanOrange\jvo\Controller;

class Webapp extends Standard {
 
 public function process() {
  
  $this->setTemplate('webapp');
  
  $this->setContentType("Content-type: application/x-web-app-manifest+json; charset=UTF-8");
  
 }
 
}
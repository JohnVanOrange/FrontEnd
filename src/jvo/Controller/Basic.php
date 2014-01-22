<?php
namespace JohnVanOrange\jvo\Controller;

class Basic extends Standard {
 
 public function process() {
  $this->setTemplate($this->route->get_page());
 }
 
}
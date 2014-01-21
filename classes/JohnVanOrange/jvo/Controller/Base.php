<?php
namespace JohnVanOrange\jvo\Controller;

class Base {
 
 public $route;
 
 public function __construct($route) {
  $this->route = $route;
 }
 
 public function load() {
  $this->process();
 }

}

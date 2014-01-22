<?php
namespace JohnVanOrange\jvo\Controller\Admin;

class Admin extends \JohnVanOrange\jvo\Controller\Standard {
 
 protected function setupInterface() {
  $this->interface = new \JohnVanOrange\jvo\SiteInterface\Admin;
 }

}
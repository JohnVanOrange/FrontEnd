<?php
namespace JohnVanOrange\jvo\SiteInterface;

class Admin extends Standard {

 public function __construct() {
  parent::__construct();
  try {
   if (!isset($this->data['is_admin'])) throw new \Exception('Must be an admin to access', 401);
  }
  catch(\Exception $e) {
   $this->exceptionHandler($e);
  }
 }

}
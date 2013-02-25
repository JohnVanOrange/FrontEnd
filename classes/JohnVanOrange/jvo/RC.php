<?php
namespace JohnVanOrange\jvo;

class RC extends Base {

 private $repo;

 public function __construct($options=array()) {
  parent::__construct();
  $this->repo = new \GitElephant\Repository(ROOT_DIR);
 }
 
 public function branch($options=array()) {
  if ($_SERVER['REMOTE_ADDR'] != RC_SERVER) throw new \Exception('IP not allowed access',401);
  return $this->repo->getMainBranch()->getName();
 }
 
 public function checkout($options=array()) {
  if ($_SERVER['REMOTE_ADDR'] != RC_SERVER) throw new \Exception('IP not allowed access',401);
  $this->repo->checkout($options['branch']);
 }

}
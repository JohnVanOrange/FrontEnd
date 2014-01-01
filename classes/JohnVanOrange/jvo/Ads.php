<?php
namespace JohnVanOrange\jvo;

class Ads extends Base {
 
 public function __construct() {
  parent::__construct();
 }
 
 /**
  * Get ad
  *
  * Get a random ad.
  *
  * @api
  */
  
 public function get() {
  $query = new \Peyote\Select('ads');
  $query->columns('title, ASIN')
        ->where('active', '=', 1)
        ->orderBy('RAND()')
        ->limit(1);
  $ad = $this->db->fetch($query)[0];
  return $ad;
 }
 
}
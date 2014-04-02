<?php
namespace JohnVanOrange\jvo\ImageFilter;

class Random extends Base {

 public function __construct($options = NULL) {
  parent::__construct($options);
 }
 
 protected function sort() {
  $this->orderBy('RAND()');
 }
 
 protected function limit_process() {
  $this->limit(1);
 }

}
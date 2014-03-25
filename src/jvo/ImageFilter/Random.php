<?php
namespace JohnVanOrange\jvo\ImageFilter;

class Random extends Base {

 public function __construct(\Peyote\Query $query, $options = NULL) {
  parent::__construct($query, $options);
 }
 
 protected function sort() {
  $this->query->orderBy('RAND()');
 }
 
 protected function limit() {
  $this->query->limit(1);
 }

}
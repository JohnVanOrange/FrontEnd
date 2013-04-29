<?php
namespace JohnVanOrange\jvo;

use PDO;

class DB extends PDO {

 public function __construct($dsn, $user, $pass, $options=array()) {
  parent::__construct($dsn, $user, $pass, $options);
  $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
  $this->setAttribute(PDO::ATTR_PERSISTENT,TRUE);
 }

 public function __destruct() {
 }

 public function fetch(\Peyote\Query $query) {
  $s = $this->prepare($query->compile());
  $s->execute($query->getParams());
  return $s->fetchAll();
 }
}
<?php
namespace JohnVanOrange\jvo;

use PDO;

class DB extends PDO {
 
 private $dsn, $user, $pass, $dboptions, $setup;

 public function __construct($dsn, $user, $pass, $options=array()) {
  $this->dsn = $dsn; $this->user = $user; $this->pass = $pass; $this->dboptions = $options; $this->setup = FALSE;
 }

 public function __destruct() {
 }

 public function fetch(\Peyote\Query $query) {
  if (!$this->setup) $this->setup();
  $q = $query->compile();
  $s = $this->prepare($q);
  $s->execute($query->getParams());
  return $s->fetchAll();
 }

 private function setup() {
  $this->setup = TRUE;
  parent::__construct($this->dsn, $this->user, $this->pass, $this->dboptions);
  $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
  $this->setAttribute(PDO::ATTR_PERSISTENT,TRUE);
 }
 
}
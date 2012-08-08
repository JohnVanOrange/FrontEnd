<?php
require_once(ROOT_DIR.'/classes/base.class.php');

class Report extends Base {
 
 public function __construct() {
  parent::__construct();
 }
 
 public function all($options=array()) {
  $sql = 'SELECT id, value FROM report_types WHERE public = "1";';
  return $this->db->fetch($sql);
 }
 
}
?>
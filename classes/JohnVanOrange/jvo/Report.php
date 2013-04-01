<?php
namespace JohnVanOrange\jvo;

class Report extends Base {
 
 public function __construct() {
  parent::__construct();
 }
 
 public function all() {
  $sql = 'SELECT id, value FROM report_types WHERE public = "1";';
  return $this->db->fetch($sql);
 }
 
 public function get($id=NULL) {
  if (!isset($id)) return $this->all();
  $sql = 'SELECT id, value FROM report_types WHERE id = :id;';
  $val = array(
   ':id' => $id
  );
  return $this->db->fetch($sql,$val);
 }
 
}
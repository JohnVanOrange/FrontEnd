<?php
namespace JohnVanOrange\jvo;
use Exception;

class Report extends Base {
 
 public function __construct() {
  parent::__construct();
 }
 
 public function all($options=array()) {
  $sql = 'SELECT id, value FROM report_types WHERE public = "1";';
  return $this->db->fetch($sql);
 }
 
 public function get($options=array()) {
  if (!$options['id']) return $this->all();
  $sql = 'SELECT id, value FROM report_types WHERE id = :id;';
  $val = array(
   ':id' => $options['id']
  );
  return $this->db->fetch($sql,$val);
 }
 
}
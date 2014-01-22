<?php
namespace JohnVanOrange\jvo;

class Report extends Base {
 
 public function __construct() {
  parent::__construct();
 }
 
 /**
  * All report types
  *
  * Get a list of all image report types.
  *
  * @api
  */
 
 public function all() {
  $query = new \Peyote\Select('report_types');
  $query->columns('id, value')
        ->where('public', '=', 1);
  return $this->db->fetch($query);
 }
 
 /**
  * Get report type
  *
  * Retrieve a specific report type
  *
  * @api
  * 
  * @param int $id If given, will return just the specific type of report that is specified by the id. If no id given, will return return all the results, same as report/all.
  */
 
 public function get($id=NULL) {
  if (!isset($id)) return $this->all();
  $query = new \Peyote\Select('report_types');
  $query->columns('id, value')
        ->where('id', '=', $id);
  return $this->db->fetch($query);
 }
 
}
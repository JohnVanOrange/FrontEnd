<?php
namespace JohnVanOrange\jvo;

class Resource extends Base {
 
 private $user;

 public function __construct() {
  parent::__construct();
  $this->user = new User;
 }

 public function add($type, $image = NULL, $value = NULL, $sid = NULL) {
  $current = $this->user->current($sid);
  $user_id = NULL;
  if (isset($current['id'])) $user_id = $current['id'];
  $sql = 'INSERT INTO resources (ip, image, user_id, value, type) VALUES(:ip, :image, :user_id, :value, :type)';
  $val = array(
   ':ip' => $_SERVER['REMOTE_ADDR'],
   ':image' => $image,
   ':user_id' => $user_id,
   ':value' => $value,
   ':type' => $type
  );
  return $this->db->fetch($sql,$val);
 }
 
 public function merge($to, $from) {
  if (!$this->user->isAdmin()) throw new \Exception('Must be an admin to access method', 401);
  $sql = 'UPDATE resources SET image = :to WHERE image = :from';
  $val = array(
   ':to' => $to,
   ':from' => $from
  );
  $this->db->fetch($sql,$val);
  return TRUE;
 }
 
}
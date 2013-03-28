<?php
namespace JohnVanOrange\jvo;

class Resource extends Base {
 
 private $user;

 public function __construct($options=array()) {
  parent::__construct();
  $this->user = new User;
 }

 public function add($options=array()) {
  if (!isset($options['value'])) $options['value'] = NULL;
  if (!isset($options['type'])) $options['type'] = NULL;
  $current = $this->user->current($options);
  $sql = 'INSERT INTO resources (ip, image, user_id, value, type) VALUES(:ip, :image, :user_id, :value, :type)';
  $val = array(
   ':ip' => $_SERVER['REMOTE_ADDR'],
   ':image' => $options['image'],
   ':user_id' => $current['id'],
   ':value' => $options['value'],
   ':type' => $options['type']
  );
  return $this->db->fetch($sql,$val);
 }
 
 public function merge($options=array()) {
  if (!$this->user->isAdmin()) throw new \Exception('Must be an admin to access method', 401);
  $sql = 'UPDATE resources SET image = :to WHERE image = :from';
  $val = array(
   ':to' => $options['to'],
   ':from' => $options['from']
  );
  $this->db->fetch($sql,$val);
  return TRUE;
 }
 
}
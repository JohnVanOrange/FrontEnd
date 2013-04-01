<?php
namespace JohnVanOrange\jvo;

class Refresh extends Base {
 
 private $user;
 
 public function __construct() {
  parent::__construct();
  $this->user = new User;
 }
 
 public function set($value = 10, $sid=NULL) {
  $value = intval($value);
  if ($value < 0) $value = 0;
  $user = $this->user->current($sid);
  if (!$user) throw new \Exception('Must be logged in to set refresh time');
  $sql = 'UPDATE users SET refresh = :refresh WHERE id = :user';
  $val = array(
   ':refresh' => $value,
   ':user' => $user['id']
  );
  $this->db->fetch($sql,$val);
  $message = 'Automatic refresh removed';
  if ($value) $message = 'Automatic refresh time updated to '.$value.' seconds';
  return array(
   'message' => $message,
   'refresh' => $value
  );
 }
 
 public function remove($sid=NULL) {
  return $this->set(0, $sid);
 }
 
 public function get($sid=NULL) {
  $user = $this->user->current($sid);
  if (!$user) throw new \Exception('Must be logged in to get refresh time');
  return $user['refresh'];
 }
 
}
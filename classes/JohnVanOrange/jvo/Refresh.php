<?php
namespace JohnVanOrange\jvo;
use Exception;

class Refresh extends Base {
 
 private $user;
 
 public function __construct() {
  parent::__construct();
  $this->user = new User;
 }
 
 public function set($options=array()) {
  if (!isset($options['value'])) $options['value'] = 10;
  $options['value'] = intval($options['value']);
  if ($options['value'] < 0) $options['value'] = 0;
  $user = $this->user->current($options);
  if (!$user) throw new Exception('Must be logged in to set refresh time');
  $sql = 'UPDATE users SET refresh = :refresh WHERE id = :user';
  $val = array(
   ':refresh' => $options['value'],
   ':user' => $user['id']
  );
  $this->db->fetch($sql,$val);
  $message = 'Automatic refresh removed';
  if ($options['value']) $message = 'Automatic refresh time updated to '.$options['value'].' seconds';
  return array(
   'message' => $message,
   'refresh' => $options['value']
  );
 }
 
 public function remove($options=array()) {
  return $this->set(array('value'=>0));
 }
 
 public function get($options=array()) {
  $user = $this->user->current($options);
  if (!$user) throw new Exception('Must be logged in to get refresh time');
  return $user['refresh'];
 }
 
}
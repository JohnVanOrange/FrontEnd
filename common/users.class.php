<?php
require_once('../settings.inc');
require_once(ROOT_DIR.'/common/base.class.php');

class Users extends Base {
 
 public function __construct() {
  parent::__construct();
 }
 
 private function getSecureID() {
  return $this->generateUID(16);
 }
 
 private function passhash($pass, $salt) {
  return md5($salt.$pass);
 }
 
 public function login($options=array()) {
  $sql = 'SELECT * FROM users WHERE username = :username LIMIT 1';
  $val = array(
   ':username' => $options['username']
  );
  $userdata - $this->db->fetch($sql,$val);
  $userdata = $userdata[0];
  if (!$userdata) throw new Exception('User not found');
  $pwhash = $this->passhash($options['password'],$userdata['salt']);
  if ($pwhash != $userdata['password']) throw new Exception('Invalid password');
  #succesfully login
  $sid = $this->getSecureID();
  setcookie('session', $sid, time()+60*60*24*365, '/');
  $sql = 'INSERT INTO sessions(user_id, sid) VALUES("'.$userdata['id'].'","'.$sid.'");';
  $this->db->fetch($sql);
 }
}
?>
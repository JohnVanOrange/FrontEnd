<?php
require_once(ROOT_DIR.'/common/base.class.php');

class User extends Base {

 private $sid;
 
 public function __construct() {
  parent::__construct();
  if ($_COOKIE['sid']) $this->setSID($_COOKIE['sid']);
 }
 
 private function getSecureID() {
  return $this->generateUID(16);
 }
 
 private function passhash($pass, $salt) {
  return md5($salt.$pass);
 }

 public function get($options=array()) {
  switch ($options['search_by']) {
   case 'username':
    $sql = 'SELECT id,username,type FROM users WHERE username = :value';
   break;
   case 'id':
   default:
    $sql = 'SELECT id,username,type FROM users WHERE id = :value';
   break;
  }
  $val = array(
   ':value' => $options['value']
  );
  $user = $this->db->fetch($sql,$val);
  return $user[0];
 }

 public function current($options=array()) {
  $sid = $this->getSID();
  if ($options['sid']) $sid = $options['sid'];
  $sql = 'SELECT user_id FROM sessions WHERE sid = :sid LIMIT 1';
  $val = array(
   ':sid' => $sid
  );
  $user = $this->db->fetch($sql,$val);
  return $this->get(array('value'=>$user[0]['user_id']));
 }

 private function getSID() {
  return $this->sid;
 }

 private function setSID($sid) {
  $this->sid = $sid;
 }
 
 public function login($options=array()) {
  $sql = 'SELECT * FROM users WHERE username = :username LIMIT 1';
  $val = array(
   ':username' => $options['username']
  );
  $userdata = $this->db->fetch($sql,$val);
  $userdata = $userdata[0];
  if (!$userdata) throw new Exception('User not found');
  $pwhash = $this->passhash($options['password'],$userdata['salt']);
  if ($pwhash != $userdata['password']) throw new Exception('Invalid password');
  #succesfully login
  $sid = $this->getSecureID();
  setcookie('sid', $sid, time()+60*60*24*365, '/');
  $sql = 'INSERT INTO sessions(user_id, sid) VALUES("'.$userdata['id'].'","'.$sid.'");';
  $this->db->fetch($sql);
  return array(
   'message' => 'Login successful.',
   'sid' => $sid
  );
 }

 public function logout($options=array()) {
  $sid = $this->getSID();
  if ($options['sid']) $sid = $options['sid'];
  if ($options['sid']) $this->sid = $options['sid'];
  $sql = 'DELETE FROM sessions WHERE sid = :sid';
  $val = array(
   ':sid' => $sid
  );
  $this->db->fetch($sql,$val);
  setcookie('sid', '', 1, '/');
  return array(
   'message' => 'Logged out.'
  );
 }

 public function add($options=array()) {
  if (!$options['username']) throw new Exception('No username specified');
  if (!$options['password']) throw new Exception('No password specified');
  if (!$options['email']) throw new Exception('No email specified');
  $salt = $this->getSecureID();
  $sql = 'INSERT INTO users(username, password, salt, email) VALUES(:username, :password, :salt, :email)';
  $val = array(
   ':username' => $options['username'],
   ':password' => $this->passhash($options['password'],$salt),
   ':salt' => $salt,
   ':email' => $options['email']
  );
  $this->db->fetch($sql,$val);
  return array(
   'message' => 'User added.'
  );
 }
}
?>

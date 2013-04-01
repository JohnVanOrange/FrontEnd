<?php
namespace JohnVanOrange\jvo;

class User extends Base {

 private $sid;
 
 public function __construct() {
  parent::__construct();
  if (isset($_COOKIE['sid'])) $this->setSID($_COOKIE['sid']);
 }
 
 private function getSecureID() {
  return $this->generateUID(16);
 }
 
 private function passhash($pass, $salt) {
  return md5($salt.$pass);
 }
 
 public function isAdmin($sid=NULL) {
  $user = $this->current($sid);
  if (!isset($user['type'])) $user['type'] = NULL;
  if ($user['type']>= 2) return TRUE;
  return FALSE;
 }
 
 protected function isLoggedIn($sid=NULL) {
  $user = $this->current($sid);
  if ($user) return TRUE;
  return FALSE;
 }

 public function get($value, $search_by=NULL) {
  switch ($search_by) {
   case 'username':
    $sql = 'SELECT id,username,type,email,theme, refresh FROM users WHERE username = :value';
   break;
   case 'id':
   default:
    $sql = 'SELECT id,username,type,email,theme, refresh FROM users WHERE id = :value';
   break;
  }
  $val = array(
   ':value' => $value
  );
  $user = $this->db->fetch($sql,$val);
  if (isset($user[0])) $user = $user[0];
  if (isset($user['email'])) $user['email_hash'] = md5($user['email']);
  unset($user['email']);
  return $user;
 }

 public function current($sid=NULL) {
  if (!isset($sid)) $sid = $this->getSID();
  $sql = 'SELECT user_id FROM sessions WHERE sid = :sid LIMIT 1';
  $val = array(
   ':sid' => $sid
  );
  $user = $this->db->fetch($sql,$val);
  $user_id = NULL;
  if (isset($user[0]['user_id'])) $user_id = $user[0]['user_id'];
  return $this->get($user_id);
 }

 private function getSID() {
  return $this->sid;
 }

 private function setSID($sid) {
  $this->sid = $sid;
 }
 
 public function login($username, $password) {
  $sql = 'SELECT * FROM users WHERE username = :username LIMIT 1';
  $val = array(
   ':username' => $username
  );
  $userdata = $this->db->fetch($sql,$val);
  $userdata = $userdata[0];
  if (!$userdata) throw new \Exception('User not found');
  $pwhash = $this->passhash($password,$userdata['salt']);
  if ($pwhash != $userdata['password']) throw new \Exception('Invalid password');
  #succesfully login
  $sid = $this->getSecureID();
  $this->setCookie('sid', $sid);
  $sql = 'INSERT INTO sessions(user_id, sid) VALUES("'.$userdata['id'].'","'.$sid.'");';
  $this->db->fetch($sql);
  return array(
   'message' => 'Login successful.',
   'sid' => $sid
  );
 }

 public function logout($sid=NULL) {
  if (!isset($sid)) $sid = $this->getSID();
  $sql = 'DELETE FROM sessions WHERE sid = :sid';
  $val = array(
   ':sid' => $sid
  );
  $this->db->fetch($sql,$val);
  $this->setCookie('sid','', 1);
  return array(
   'message' => 'Logged out.'
  );
 }

 public function add($username, $password, $email) {
  $salt = $this->getSecureID();
  $sql = 'INSERT INTO users(username, password, salt, email) VALUES(:username, :password, :salt, :email)';
  $val = array(
   ':username' => $username,
   ':password' => $this->passhash($password,$salt),
   ':salt' => $salt,
   ':email' => $email
  );
  $this->db->fetch($sql,$val);
  return array(
   'message' => 'User added.'
  );
 }
 
 public function saved($username, $sid=NULL) {
  $current = $this->current($sid);
  $user = $this->get($username, 'username');
  if ($current['id'] != $user['id']) throw new \Exception('This users saved images are not publicly shared');
  $sql = 'SELECT image FROM resources WHERE user_id = '.$user['id'].' AND type = "save"';
  $results = $this->db->fetch($sql);
  $image = new Image();
  foreach ($results as $result) {
   try {
    $return[] = $image->get($result['image']);
   }
   catch(\Exception $e) {
    if ($e->getCode() != 403) {
     throw new \Exception($e);
    }
   }
  }
  return $return;
 }
 
 public function uploaded($username, $sid=NULL) {
  $current = $this->current($sid);
  $user = $this->get($username, 'username');
  if ($current['id'] != $user['id']) throw new \Exception('This users uploaded images are not publicly shared');
  $sql = 'SELECT image FROM resources WHERE user_id = '.$user['id'].' AND type = "upload"';
  $results = $this->db->fetch($sql);
  $image = new Image();
  foreach ($results as $result) {
   try {
    $return[] = $image->get($result['image']);
   }
   catch(\Exception $e) {
    if ($e->getCode() != 403) {
     throw new \Exception($e);
    }
   }
  }
  return $return;
 } 
 
 
}
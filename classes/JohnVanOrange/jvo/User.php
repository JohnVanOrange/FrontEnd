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
 
 /**
  * Get user
  *
  * Retrieve details about a user account.
  *
  * @api
  * 
  * @param mixed $value By default, this is the user_id of an account. This can also be a username if the "search_by" parameter is set to "username".
  * @param string $search_by Valid values are 'id' or 'username'.
  */

 public function get($value, $search_by='id') {
  $query = new \Peyote\Select('users');
  $query->columns('id,username,type,email,theme,refresh');
  switch ($search_by) {
   case 'username':
    $query->where('username', '=', $value);
   break;
   case 'id':
   default:
    $query->where('id', '=', $value);
   break;
  }
  $user = $this->db->fetch($query);
  if (isset($user[0])) $user = $user[0];
  if (isset($user['email'])) $user['email_hash'] = md5($user['email']);
  unset($user['email']);
  return $user;
 }
 
 /**
  * Current user
  *
  * Retrieve user details of currently logged in account.
  *
  * @api
  * 
  * @param string $sid Session ID that is provided when logged in. This is also set as a cookie. If sid cookie headers are sent, this value is not required.
  */

 public function current($sid=NULL) {
  if (!isset($sid)) $sid = $this->getSID();
  $query = new \Peyote\Select('sessions');
  $query->columns('user_id')
        ->where('sid', '=', $sid)
        ->limit(1);
  $user = $this->db->fetch($query);
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
 
 /**
  * Account login
  *
  * Login to an account.
  *
  * @api
  * 
  * @param string $username Valid username.
  * @param string $password Valid password.
  */
 
 public function login($username, $password) {
  $query = new \Peyote\Select('users');
  $query->where('username', '=', $username)
        ->limit(1);
  $userdata = $this->db->fetch($query);
  if (!isset($userdata[0])) throw new \Exception('User not found');
  $userdata = $userdata[0];
  $pwhash = $this->passhash($password,$userdata['salt']);
  if ($pwhash != $userdata['password']) throw new \Exception('Invalid password');
  //succesfully login
  $sid = $this->getSecureID();
  $this->setCookie('sid', $sid);
  $query = new \Peyote\Insert('sessions');
  $query->columns(['user_id', 'sid'])
        ->values([$userdata['id'],$sid]);
  $this->db->fetch($query);
  return array(
   'message' => 'Login successful.',
   'sid' => $sid
  );
 }
 
 /**
  * Logout account
  *
  * Logout of an account.
  *
  * @api
  * 
  * @param string $sid Session ID that is provided when logged in. This is also set as a cookie. If sid cookie headers are sent, this value is not required.
  */

 public function logout($sid=NULL) {
  if (!isset($sid)) $sid = $this->getSID();
  $query = new \Peyote\Delete('sessions');
  $query->where('sid', '=', $sid);
  $this->db->fetch($query);
  $this->setCookie('sid','', 1);
  return array(
   'message' => 'Logged out.'
  );
 }
 
 /**
  * Add user
  *
  * Create new user account and login as that user.
  *
  * @api
  * 
  * @param string $username Any unique string used to login to an account
  * @param string $password Any string
  * @param string $email This can also be any string, but a valid email address would be required to do any password recovery.
  */

 public function add($username, $password, $email) {
  if (!isset($username)) throw new \Exception('No username specified');
  if (!isset($password)) throw new \Exception('No password specified');
  if (!isset($email)) throw new \Exception('No email specified');
  $salt = $this->getSecureID();
  $query = new \Peyote\Insert('users');
  $query->columns(['username', 'password', 'salt', 'email'])
        ->values([$username, $this->passhash($password, $salt), $salt, $email]);
  $this->db->fetch($query);
  $login = $this->login($username, $password);
  return array(
   'message' => 'User added.',
   'sid' => $login['sid']
  );
 }
 
 /**
  * User's saved images
  *
  * Load all saved images for a user account.
  *
  * @api
  * 
  * @param string $username Provide the username of the user to view their saved images. Currently can only view your own saved images when logged in.
  * @param string $sid Session ID that is provided when logged in. This is also set as a cookie. Only required if the cookie sid header is not sent.
  */
 
 public function saved($username, $sid=NULL) {
  $current = $this->current($sid);
  $user = $this->get($username, 'username');
  if ($current['id'] != $user['id']) throw new \Exception('This users saved images are not publicly shared');
  $query = new \Peyote\Select('resources');
  $query->where('user_id', '=', $user['id'])
        ->where('type', '=', 'save');
  $results = $this->db->fetch($query);
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
 
 /**
  * User's uploaded images
  *
  * Load all uploaded images for a user account.
  *
  * @api
  * 
  * @param string $username Provide the username of the user to view their saved images. Currently can only view your own saved images when logged in.
  * @param string $sid Session ID that is provided when logged in. This is also set as a cookie. Only required if the cookie sid header is not sent.
  */
 
 public function uploaded($username, $sid=NULL) {
  $current = $this->current($sid);
  $user = $this->get($username, 'username');
  if ($current['id'] != $user['id']) throw new \Exception('This users uploaded images are not publicly shared');
  $query = new \Peyote\Select('resources');
  $query->where('user_id', '=', $user['id'])
        ->where('type', '=', 'upload');
  $results = $this->db->fetch($query);
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
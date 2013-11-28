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
  if (!$user) throw new \Exception('User not found', 1200);
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
  if (isset($user[0]['user_id'])) {
   $user_id = $user[0]['user_id'];
   try {
    $current = $this->get($user_id);
   }
   catch(\Exception $e) {
    if ($e->getCode() == 1200) {
     $current = FALSE;
    }
    else {
     throw new \Exception($e);
    }
   }
  }
  else {
   $current = FALSE;
  }
 return $current;
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
  $query = new \Peyote\Select('users');
  $query->where('username', '=', $username)
        ->limit(1);
  $result = $this->db->fetch($query);
  if ($result) throw new \Exception('Username already exists');
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
  * @param string $username Provide the username of the user to view their saved images. Currently can only view your own saved images when logged in. If not set, the currently logged in user will be used.
  * @param string $sid Session ID that is provided when logged in. This is also set as a cookie. Only required if the cookie sid header is not sent.
  */
 
 public function saved($username = NULL, $sid=NULL) {
  $current = $this->current($sid);
  if ($username) {
   $user = $this->get($username, 'username');
   if ($current['id'] != $user['id']) throw new \Exception('This users saved images are not publicly shared');
  } else {
   $user = $current;
  }
  $query = new \Peyote\Select('resources');
  $query->where('user_id', '=', $user['id'])
        ->where('type', '=', 'save');
  $results = $this->db->fetch($query);
  $image = new Image();
  $return = [];
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
  * @param string $username Provide the username of the user to view their saved images. Currently can only view your own saved images when logged in. If not set, the currently logged in user will be used.
  * @param string $sid Session ID that is provided when logged in. This is also set as a cookie. Only required if the cookie sid header is not sent.
  */
 
 public function uploaded($username = NULL, $sid=NULL) {
  $current = $this->current($sid);
  if ($username) {
   $user = $this->get($username, 'username');
   if ($current['id'] != $user['id']) throw new \Exception('This users uploaded images are not publicly shared');
  } else {
   $user = $current;
  }
  $query = new \Peyote\Select('resources');
  $query->where('user_id', '=', $user['id'])
        ->where('type', '=', 'upload');
  $results = $this->db->fetch($query);
  $image = new Image();
  $return = [];
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
  * Request a reset of the users password
  *
  * Sends a password reset email to the user.
  *
  * @api
  *
  * @param string $username Provide the username of the user to send the password reset email to.
  */
 
 public function requestPwReset($username) {
  $user = $this->get($username, 'username');
  $uid = $this->getSecureID();
  // This has to manually add the resource as Resource/add doesn't have a way to specifiy a userID, it has to be derived from a SID.
  $data = [
   'ip' => $_SERVER['REMOTE_ADDR'],
   'user_id' => $user['id'],
   'value' => $uid,
   'type' => 'pwreset',
   'public' => 0
  ];
  $query = new \Peyote\Insert('resources');
  $query->columns(array_keys($data))
        ->values(array_values($data));
  $this->db->fetch($query);
  $body = 'There was a password reset request sent from '. SITE_NAME . ".\n\n";
  $body .= "Username:\n";
  $body .= $user['username']."\n\n";
  $body .= "Follow link to provide new password:\n";
  $body .= WEB_ROOT.'changepw?resetkey='.$uid."\n\n";
  //need to get email address from db as $user->get doesn't return it for security reasons
  $query = new \Peyote\Select('users');
  $query->columns('email')
        ->where('username', '=', $username);
  $email = $this->db->fetch($query);
  $message = new Mail();
  $message->setTo([$email[0]['email']])
          ->setFrom(SITE_EMAIL, SITE_NAME)
          ->setSubject('Password reset request for '. SITE_NAME)
          ->setBody($body);
  $message->send();
  return array(
   'message' => 'Reset email sent.'
  );
 }
 
 /**
  * Change password
  *
  * Change password for account
  *
  * @api
  *
  * @param string $password New password
  * @param string $auth This is either a valid SID of a logged in user, or a password reset ID
  * @param string $type Valid values are "sid" or "pwreset"
  */
 
 public function changepw($password, $auth, $type = 'sid') {
  if (!$password) throw new \Exception('Password is blank');
  switch ($type) {
   case 'pwreset':
    $query = new \Peyote\Select('resources');
    $query->columns('user_id')
          ->where('value', '=', $auth)
          ->limit(1);
    $user_id = $this->db->fetch($query)[0]['user_id'];
    if (!$user_id) throw new \Exception('Password reset key not found');
    $query = new \Peyote\Delete('resources');
    $query->where('value', '=', $auth)
          ->limit(1);
    $this->db->fetch($query);
    break;
   default:
    $query = new \Peyote\Select('sessions');
    $query->columns('user_id')
          ->where('sid', '=', $auth)
          ->limit(1);
    $user_id = $this->db->fetch($query)[0]['user_id'];
    if (!$user_id) throw new \Exception('User session error');
    break;
  }
  $salt = $this->getSecureID();
  $query = new \Peyote\Update('users');
  $query->set([
               'password' => $this->passhash($password, $salt),
               'salt' => $salt
              ])
        ->where('id', '=', $user_id);
  $this->db->fetch($query);
  return [
   'message' => 'Password changed'
  ];
 }
 
 
}
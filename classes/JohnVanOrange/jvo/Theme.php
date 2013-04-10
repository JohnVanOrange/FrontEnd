<?php
namespace JohnVanOrange\jvo;

class Theme extends Base {
 
 private $user;
 
 public function __construct() {
  parent::__construct();
  $this->user = new User;
 }
 
 /**
  * Set theme
  *
  * Set the UI theme.
  *
  * @api
  * 
  * @param string $theme  Allowed values are "light" or "dark". If the user is logged in, this setting is associated with their account. Otherwise, this value is stored as a browser cookie.
  * @param string $sid Session ID that is provided when logged in. This is also set as a cookie. Only required if the cookie sid header is not sent and it's desired to have this data saved with a user account.
  */
 
 public function set($theme, $sid=NULL) {
  switch ($theme) {
   case 'light':
   case 'dark':
   break;
   default:
    throw new \Exception('Not a valid theme');
   break;
  }
  $user = $this->user->current($sid);
  if ($user) {
   $sql = 'UPDATE users SET theme = :theme WHERE id = :user';
   $val = array(
    ':theme' => $theme,
    ':user' => $user['id']
   );
   $this->db->fetch($sql,$val);
  }
  else {
   $this->setCookie('theme',$theme);
  }
  return array(
   'message' => 'Theme updated.'
  );
 }
 
 /**
  * Get theme
  *
  * Retrieve currently set UI theme.
  *
  * @api
  * 
  * @param string $sid Session ID that is provided when logged in. This is also set as a cookie. Only required if the cookie sid header is not sent and it's desired to have this data saved with a user account.
  */
 
 public function get($sid=NULL) {
  $user = $this->user->current($sid);
  if ($user) {
   return $user['theme'];
  }
  if ($_COOKIE['theme']) {
   return $_COOKIE['theme'];
  }
  return 'dark';
 }
 
}
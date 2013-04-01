<?php
namespace JohnVanOrange\jvo;

class Theme extends Base {
 
 private $user;
 
 public function __construct() {
  parent::__construct();
  $this->user = new User;
 }
 
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
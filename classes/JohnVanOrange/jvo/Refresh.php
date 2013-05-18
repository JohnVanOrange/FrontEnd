<?php
namespace JohnVanOrange\jvo;

class Refresh extends Base {
 
 private $user;
 
 public function __construct() {
  parent::__construct();
  $this->user = new User;
 }
 
 /**
  * Set auto-refresh
  *
  * Set the auto-refresh time.
  *
  * @api
  * 
  * @param int $value The time in seconds for the auto refresh.
  * @param string $sid Session ID that is provided when logged in. This is also set as a cookie. Only required if the cookie sid header is not sent.
  */
 
 public function set($value = 10, $sid=NULL) {
  $value = intval($value);
  if ($value < 0) $value = 0;
  $user = $this->user->current($sid);
  if (!$user) throw new \Exception('Must be logged in to set refresh time');
  $query = new \Peyote\Update('users');
  $query->set(['refresh' => $value])
        ->where('id', '=', $user['id']);
  $this->db->fetch($query);
  $message = 'Automatic refresh removed';
  if ($value) $message = 'Automatic refresh time updated to '.$value.' seconds';
  return array(
   'message' => $message,
   'refresh' => $value
  );
 }
 
 /**
  * Remove auto-refresh
  *
  * Removes the auto-refresh time. This is the same as setting /refresh/set to 0 seconds.
  *
  * @api
  * 
  * @param string $sid Session ID that is provided when logged in. This is also set as a cookie. Only required if the cookie sid header is not sent.
  */
 
 public function remove($sid=NULL) {
  return $this->set(0, $sid);
 }
 
  /**
  * Get auto-refresh
  *
  * Retrieve current page auto-refresh time. Must be logged in to use.
  *
  * @api
  * 
  * @param string $sid Session ID that is provided when logged in. This is also set as a cookie. Only required if the cookie sid header is not sent.
  */
  
 public function get($sid=NULL) {
  $user = $this->user->current($sid);
  if (!$user) throw new \Exception('Must be logged in to get refresh time');
  return $user['refresh'];
 }
 
}
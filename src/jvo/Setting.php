<?php
namespace JohnVanOrange\jvo;

class Setting extends Base {
 
 public function __construct() {
  parent::__construct();
 }
 
 /**
  * Get setting
  *
  * Get the value of a setting.
  *
  * @api
  *
  * @param string $name Setting name
  */
  
 public function get($name) {
  $query = new \Peyote\Select('settings');
  $query->where('name', '=', $name);
  $result = $this->db->fetch($query)[0];
  return $result;
 }
 
 /**
  * Update setting
  *
  * Update the value of a setting. Must be logged on as admin to access this method.
  *
  * @api
  *
  * @param string $name Setting name
  * @param string $value Setting value
  * @param string $sid Session ID that is provided when logged in. This is also set as a cookie. If sid cookie headers are sent, this value is not required.
  */
 
 public function update($name, $value, $sid=NULL) {
  $user = new User;
  $current = $user->current($sid);
  if ($current['type'] < 2) throw new \Exception(_('Must be an admin to access method'), 401);
  $query = new \Peyote\Update('settings');
  $query->set(['value' => $value])
        ->where('name', '=', $name);
  $this->db->fetch($query);
  return [
   'message' => _('Setting updated')
  ];
 }
 
 /**
  * Display all settings
  *
  * Display a list of all available settings.
  *
  * @api
  */
 public function all() {
  $query = new \Peyote\Select('settings');
  $query->columns('name');
  $result = $this->db->fetch($query);
  foreach($result as $r) {
   $list[] = $r['name'];
  }
  return $list;
 }
 
}
<?php
require_once(ROOT_DIR.'/classes/base.class.php');
require_once(ROOT_DIR.'/classes/user.class.php');

class Resource extends Base {
 
 private $user;

 public function __construct($options=array()) {
  parent::__construct();
  $this->user = new User;
 }

 public function add($options=array()) {
  $current = $this->user->current($options);
  $sql = 'INSERT INTO resources (ip, image, user_id, value, type) VALUES(:ip, :image, :user_id, :value, :type)';
  $val = array(
   ':ip' => $_SERVER['REMOTE_ADDR'],
   ':image' => $options['image'],
   ':user_id' => $current['id'],
   ':value' => $options['value'],
   ':type' => $options['type']
  );
  return $this->db->fetch($sql,$val);
 }
}
?>
<?php
require_once(ROOT_DIR.'/common/db.class.php');

class Base {

 protected $db;

 public function __construct($options=array()) {
  $this->db = new DB('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
 }

 protected function remoteFetch($options=array()) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $options['url']);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 File Retrieval Bot by /u/cbulock (+'.WEB_ROOT.'bot)');
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
  curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 20);
  $response = curl_exec($ch);
  curl_close($ch);
  return $response;
 }

 protected function generateUID($length) {
  $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  $uid = '';
  for($i=0;$i<$length;$i++) {
   $uid .= $chars[rand(0,strlen($chars)-1)];
  }
  return $uid;
 }
 
 protected function log($text,$file) {
  if (!isset($this->log[$file])) {
   $this->log[$file] = fopen($file,'a');
  }
  $timestamp = date('c');
  $log = $timestamp."\t".$text."\n";
  echo $text."\n";
  return fwrite($this->log[$file],$log);
 }

}
?>

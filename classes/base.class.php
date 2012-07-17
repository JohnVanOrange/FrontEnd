<?php
require_once(ROOT_DIR.'/classes/db.class.php');

class Base {

 protected $db;
 private $logs=array();

 public function __construct($options=array()) {
  $this->db = new DB('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
 }
 
 public function __call($name, $args) {
  throw new Exception('Invalid method '.$name);
 }

 protected function remoteFetch($options=array()) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $options['url']);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 File Retrieval Bot by /u/cbulock (+'.WEB_ROOT.'bot)');
  //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE); //disable for now as prod server doesn't handle properly
  curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 180);
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
  /*if (!isset($this->log[$file])) {
   $this->log[$file] = fopen($file,'a');
  }
  $timestamp = date('c');
  $log = $timestamp."\t".$text."\n";*/
  $this->logs[] = $text;
  //return fwrite($this->log[$file],$log);
  return TRUE;
 }

 protected function getLogs() {
  return $this->logs;
 }

 public function text2slug($text) {
  $output = htmlentities($text, ENT_COMPAT, "UTF-8", false); 
  $output = preg_replace('/&([a-z]{1,2})(?:acute|lig|grave|ring|tilde|uml|cedil|caron);/i','\1',$output);
  $output = html_entity_decode($output,ENT_COMPAT, "UTF-8"); 
  $output = preg_replace('/[^a-z0-9-]+/i', '-', $output);
  $output = preg_replace('/-+/', '-', $output);
  $output = trim($output, '-');
  $output = strtolower($output);
  return $output;
 }

}
?>

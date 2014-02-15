<?php
namespace JohnVanOrange\jvo;

class PublicAPI {
 
 private $class;
 private $method;
 private $req;
 
 public function __construct() {
  Locale::get();
 }
 
 public function setClass($class) {
  $this->class = $class;
 }
 
 public function setMethod($method) {
  $this->method = $method;
 }
 
 public function setRequest($req) {
  $this->req = $req;
 }
 
 public function output() {
  $api = new API;
  
  header ('Content-type: application/json; charset=UTF-8');
  header ('Access-Control-Allow-Origin: *');
  
  try {
   $result = $api->call($this->class.'/'.$this->method, $this->req);
  }
  catch(\Exception $e) {
   $this->exceptionHandler($e);
  }
  if (!is_array($result)) $result = ['response' => $result];
  
  echo json_encode($result);
 }
 
 private function exceptionHandler($e) {
  if ($e->getCode() == 403 || $e->getCode() == 404) {
   header("HTTP/1.0 ".$e->getCode());
   $_SERVER['REDIRECT_STATUS'] = $e->getCode();
  }
  echo json_encode(array(
   'error' => $e->getCode(),
   'message' => $e->getMessage()
  ));
  die();
 }
 
}
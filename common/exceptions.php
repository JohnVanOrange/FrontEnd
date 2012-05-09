<?php

function page_exception_handler($e) {
 switch($e->getCode()) {
  case 403:
  case 404:
   $number = $e->getCode();
   include(ROOT_DIR.'/pages/error.php');
   die();
   break;
 }
}

function js_exception_handler($e) {
 echo json_encode(array(
  'error' => $e->getCode(),
  'message' => $e->getMessage()
 ));
 die();
}

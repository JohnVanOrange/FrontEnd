<?php

header ('Content-type: application/json; charset=UTF-8');

$valid_classes = array(
 'image' => 'Image',
 'user' => 'User',
 'tag' => 'Tag',
 'theme' => 'Theme'
);

try {
 switch ($class) {
  case 'image':
  case 'user':
  case 'tag':
  case 'theme':
   $class_name = $valid_classes[$class];
   require_once('classes/'.$class.'.class.php');
  break;
  default:
   throw new Exception('Invalid class/URL');
  break;
 }
 $class_obj = new $class_name;
 $result = $class_obj->{$method}($_REQUEST);
}
catch (exception $e) {
 js_exception_handler($e);
}

//If default header is set, make sure results are in JSON
if (in_array('Content-type: application/json; charset=UTF-8',headers_list())) {
 if (!is_array($result)) $result = array('response'=>$result);
 echo json_encode($result);
}
else {
 echo $result;
}
?>

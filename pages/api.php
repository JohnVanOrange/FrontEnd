<?php

$valid_classes = array(
 'image' => 'Image',
 'users' => 'Users',
 'tag' => 'Tag'
);

switch ($class) {
 case 'image':
 case 'users':
 case 'tag':
  $class_name = $valid_classes[$class];
  require_once('common/'.$class.'.class.php');
 break;
 default:
  throw new Exception('Invalid class/URL');
 break;
}

$class_obj = new $class_name;

header ('Content-type: application/json; charset=UTF-8');

try {
 $result = $class_obj->{$method}($_REQUEST);
}
catch (exception $e) {
 js_exception_handler($e);
}

echo json_encode($result);
?>

<?php

function call($method, $options=[], $js=FALSE) {
 $result = explode('/',$method);
 $class = $result[0];
 $method = $result[1];

 $valid_classes = [
  'image' => 'JohnVanOrange\jvo\Image',
  'user' => 'JohnVanOrange\jvo\User',
  'tag' => 'JohnVanOrange\jvo\Tag',
  'theme' => 'JohnVanOrange\jvo\Theme',
  'report' => 'JohnVanOrange\jvo\Report',
  'refresh' => 'JohnVanOrange\jvo\Refresh',
  'reddit' => 'JohnVanOrange\jvo\Reddit',
  'media' => 'JohnVanOrange\jvo\Media'
  ];
 try {
  switch ($class) {
   case 'image':
   case 'user':
   case 'tag':
   case 'theme':
   case 'report':
   case 'refresh':
   case 'reddit':
   case 'media':
    $class_name =  $valid_classes[$class];
    break;
   default:
    throw new Exception('Invalid class/URL');
   break;
  }

  $reflectClass = new ReflectionClass($class_name);
  $reflectMethod = $reflectClass->getMethod($method);
  $reflectParams = $reflectMethod->getParameters();
  
  $params = [];
  
  foreach($reflectParams as $param) {
   if (isset($options[$param->getName()])) $params[] = $options[$param->getName()];
  }
  
  $class_obj = new $class_name;
  
  return $reflectMethod->invokeArgs($class_obj, $params);
 }
 catch(Exception $e) {
  if ($js) js_exception_handler($e);
  page_exception_handler($e);
 }
}
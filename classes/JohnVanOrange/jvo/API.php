<?php
namespace JohnVanOrange\jvo;

class API {

 public function __construct() {
 }
 
 /*
  * Call public API methods
  *
  * Accesses allowed methods through a common interface.
  *
  * @param string $method Method name to access.
  * @param mixed[] $params Associated array of named parameters and their values.
  */
 
 public function call($method, $params=[]) {
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
     'media' => 'JohnVanOrange\jvo\Media',
     'ads' => 'JohnVanOrange\jvo\Ads'
     ];
    switch ($class) {
     case 'image':
     case 'user':
     case 'tag':
     case 'theme':
     case 'report':
     case 'refresh':
     case 'reddit':
     case 'media':
     case 'ads':
      $class_name =  $valid_classes[$class];
      break;
     default:
      throw new \Exception(_('Invalid class/URL'));
     break;
    }
   
    $reflectClass = new \ReflectionClass($class_name);
    $reflectMethod = $reflectClass->getMethod($method);
    $reflectParams = $reflectMethod->getParameters();
     
    $indexed_params = [];
     
    foreach($reflectParams as $param) {
     if (isset($params[$param->getName()])) {
      $indexed_params[] = $params[$param->getName()];
     } else {
      if ($param->isOptional()) {
       $indexed_params[] = $param->getDefaultValue();
      } else {
       $indexed_params[] = NULL;
      }
     }
    }
     
    $class_obj = new $class_name;

    return $reflectMethod->invokeArgs($class_obj, $indexed_params);
 }

}
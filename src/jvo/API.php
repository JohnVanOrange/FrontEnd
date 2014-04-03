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
     'image' => 'JohnVanOrange\core\Image',
     'user' => 'JohnVanOrange\core\User',
     'tag' => 'JohnVanOrange\core\Tag',
     'report' => 'JohnVanOrange\core\Report',
     'reddit' => 'JohnVanOrange\core\Reddit',
     'media' => 'JohnVanOrange\core\Media',
     'ads' => 'JohnVanOrange\core\Ads',
     'setting' => 'JohnVanOrange\core\Setting'
     ];
    switch ($class) {
     case 'image':
     case 'user':
     case 'tag':
     case 'report':
     case 'reddit':
     case 'media':
     case 'ads':
     case 'setting':
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
<?php
namespace JohnVanOrange\jvo;

class API extends Base {

 public function __construct() {
  parent::__construct();
 }
 
 /*
  * Call public API methods
  *
  * Accesses allowed methods through a common interface to assist with exception handling.
  *
  * @param string $method Method name to access.
  * @param array $params Associated array of named parameters and their values.
  * @param bool $js If true, the Javascript execption handler will be used.
  */
 public function call($method, $params=[], $js=FALSE) {
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
       throw new \Exception('Invalid class/URL');
      break;
     }
   
     $reflectClass = new \ReflectionClass($class_name);
     $reflectMethod = $reflectClass->getMethod($method);
     $reflectParams = $reflectMethod->getParameters();
     
     $indexed_params = [];
     
     foreach($reflectParams as $param) {
      if (isset($params[$param->getName()])) $indexed_params[] = $params[$param->getName()];
     }
     
     $class_obj = new $class_name;

     return $reflectMethod->invokeArgs($class_obj, $indexed_params);
    }
    catch(\Exception $e) {
     if ($js) js_exception_handler($e);
     page_exception_handler($e);
    }
 }

}
<?php
namespace JohnVanOrange\jvo;

require_once('smarty.php');

$template = 'docs.tpl';

$class = $route->get_data(0);

$xml = file_get_contents('docs/structure.xml');
$docs = new \SimpleXMLElement($xml);
$docs = json_decode(json_encode($docs), TRUE);

foreach ($docs['file'] as $obj) {
 $classes[] = strtolower($obj['class']['name']);
 if (strtolower($obj['class']['name']) == $class) $tpl->assign('class', process_docdata($obj['class']));
}
$tpl->assign('classes', $classes);

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");
$tpl->display($template);


function process_docdata($data) {
 $output = [];
 //lowercase the class name
 $output['name'] = strtolower($data['name']);
 //Prcoess each method in the class
 foreach ($data['method'] as $method) {
  $params = [];
  //check to see if there are any docblock tags
  if (isset($method['docblock']['tag'])) {
   //if there is only one tag, the array will be flattened
   if (count($method['docblock']['tag']) == 1) $method['docblock']['tag'][0]['@attributes'] = array_shift($method['docblock']['tag']);
   //Process each of the docblock tags
   foreach ($method['docblock']['tag'] as $tag) {
    if (isset($tag['@attributes']['name'])) {
     //Check if a api tag exists, and store this information at the top level of the method
     if ($tag['@attributes']['name'] == 'api') $method['api'] = TRUE;
     //If param tags exist, store this information higher up
     if ($tag['@attributes']['name'] == 'param') {
      $params[$tag['@attributes']['variable']] = $tag['@attributes'];
      $params[$tag['@attributes']['variable']]['variable'] = ltrim($params[$tag['@attributes']['variable']]['variable'],'$');
     }
    }
   }
  }
  //add argument data to param data
  if (isset($method['argument'])) {
   //if there is only one argument, the array will be flattened
   if (isset($method['argument']['name'])) {
    $method['argument'][0] = $method['argument'];
    unset($method['argument']['@attributes']);
    unset($method['argument']['name']);
    unset($method['argument']['default']);
    unset($method['argument']['type']);
   }
   foreach($method['argument'] as $arg) {
    //This might need to be adjusted. Default seems to be an empty array if there isn't a default. But, arrays may be valid in some cases.
    if (!is_array($arg['default'])) $params[$arg['name']]['default'] = $arg['default'];
   }
  }
  if (count($params) > 0) $method['params'] = $params;
  if (isset($method['api'])) $output['method'][] = $method;
 }
 return $output;
}
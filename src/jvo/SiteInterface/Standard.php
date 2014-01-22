<?php
namespace JohnVanOrange\jvo\SiteInterface;

class Standard {
 
 private $twig;
 private $template;
 protected $data = [];
 private $content_type;
 private $api;
 
 public function __construct() {
  $this->api = new \JohnVanOrange\jvo\API;
  
  $loader = new \Twig_Loader_Filesystem('templates');
  $this->twig = new \Twig_Environment($loader);
  $this->twig->addExtension(new \Twig_Extensions_Extension_I18n());
  
  $this->setContentType("Content-type: text/html; charset=UTF-8");
  
  $user = $this->api('user/current');
  if (!isset($user['id'])) $user['id'] = 0;
  
  $this->addData([
   'user'         => $user,
   'ga'			      => GOOGLE_ANALYTICS,
   'site_name'	  => SITE_NAME,
   'web_root'		  => WEB_ROOT,
   'hostname'		  => parse_url(WEB_ROOT)['host'],
   'show_social'	=> SHOW_SOCIAL,
   'icon_set'		  => ICON_SET,
   'site_theme'	  => THEME,
   'current_url'	=> 'http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"],
   'browser'		  => \Browser\Browser::getBrowser(),
   'locale'		    => \JohnVanOrange\jvo\Locale::get()
  ]);
  if (defined('APP_LINK')) $this->addData(['app_link' => APP_LINK]);
  if (defined('SHOW_JVON')) $this->addData(['show_jvon' => SHOW_JVON]);
  if (defined('SHOW_BRAZZ')) $this->addData(['show_brazz' => SHOW_BRAZZ]);
  if (defined('FB_APP_ID')) $this->addData(['fb_app_id' => FB_APP_ID]);
  if (defined('AMAZON_AFF')) $this->addData(['amazon_aff' => AMAZON_AFF]);
  if ($this->api('user/isAdmin')) $this->addData(['is_admin' => TRUE]);
 }
 
 public function api($method, $params=[]) {
  try {
   $result = $this->api->call($method, $params);
  }
  catch(\Exception $e) {
   $this->exceptionHandler($e);
  }
  return $result;
 }
 
 public function setContentType($content_type) {
  $this->content_type = $content_type;
 }
 
 public function template($template) {
  $this->template = $template;
 }
 
 public function addData($data) {
  $this->data = array_merge($this->data, $data);
 }
 
 public function render() {
  header($this->content_type);
  $template = $this->twig->loadTemplate($this->template . '.twig');
  return $template->render($this->data);
 }
 
 public function output() {
  echo $this->render();
 }
 
 protected function exceptionHandler($e) {
  $code = $e->getCode();
  switch($code) {
   case 403:
   case 404:
    header("HTTP/1.0 ".$code);
    $_SERVER['REDIRECT_STATUS'] = $code;
    $this->addData([
     'number'	=>	$code,
     'error_image'	=>	constant($code.'_IMAGE'),
     'rand'	=>	md5(uniqid(rand(), true))
    ]);
    $this->template('error');
    $this->output();
    die();
    break;
   default:
    $body = 'An error occured for site '. SITE_NAME . ".\n\n";
    $body .= "Error:\n";
    $body .= $e->getMessage()."\n\n";
    $body .= "Code:\n";
    $body .= $code."\n\n";
    $body .= "Page requested:\n";
    $body .= $_SERVER['REQUEST_URI']."\n\n";    
    $body .= "IP:\n";
    $body .= $_SERVER['REMOTE_ADDR'];
    $message = new \JohnVanOrange\jvo\Mail();
    $message->sendAdminMessage('Error Occured for '. SITE_NAME, $body);
    $this->addData([
     'code'	=>	$code,
     'message'	=>	$e->getMessage()
    ]);
    $this->template('exception');
    $this->output();
    die();
    break;
  }
 }
 
}
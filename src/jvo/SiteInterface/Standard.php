<?php
namespace JohnVanOrange\jvo\SiteInterface;

class Standard {

 private $twig;
 private $template;
 protected $data = [];
 private $content_type;
 private $api;
 private $browserdata;

 public function __construct() {
  $this->api = new \JohnVanOrange\API\API;
  $this->browserdata = new \JohnVanOrange\jvo\BrowserData;

  $this->twig_init();

  $this->setContentType("Content-type: text/html; charset=UTF-8");

  $user = $this->api('user/current');
  if (!isset($user['id'])) $user['id'] = 0;

  $web_root = $this->api('setting/get', ['name' => 'web_root']);

  $this->addData([
   'user'         => $user,
   'ga'			      => $this->api('setting/get', ['name' => 'google_analytics']),
   'site_name'	  => $this->api('setting/get', ['name' => 'site_name']),
   'web_root'		  => $web_root,
   'hostname'		  => parse_url($web_root)['host'],
   'show_social'	=> $this->api('setting/get', ['name' => 'show_social']),
   'icon_set'		  => $this->api('setting/get', ['name' => 'icon_set']),
   'current_url'	=> 'http://'.$this->browserdata->server("SERVER_NAME").$this->browserdata->server("REQUEST_URI"),
   'browser'		  => \Browser\Browser::getBrowser(),
   'locale'		    => \JohnVanOrange\API\Locale::get()
  ]);

  $filter = $this->browserdata->cookie('filter'); if ($filter) $this->addData(['filter' => TRUE]);
  $site_theme = $this->api('setting/get', ['name' => 'theme']); if ($site_theme) $this->addData(['site_theme' => $site_theme]);
  $app_link = $this->api('setting/get', ['name' => 'app_link']); if ($app_link) $this->addData(['app_link' => $app_link]);
  $show_brazz = $this->api('setting/get', ['name' => 'show_brazz']); if ($show_brazz) $this->addData(['show_brazz' => $show_brazz]);
  $show_jvon = $this->api('setting/get', ['name' => 'show_jvon']); if ($show_jvon) $this->addData(['show_jvon' => $show_jvon]);
  $fb_app_id = $this->api('setting/get', ['name' => 'fb_app_id']); if ($fb_app_id) $this->addData(['fb_app_id' => $fb_app_id]);
  if ($this->api('user/isAdmin')) $this->addData(['is_admin' => TRUE]);
 }

 private function twig_init() {
  $loader = new \Twig_Loader_Filesystem('templates');
  $this->twig = new \Twig_Environment($loader);
  $this->twig->addExtension(new \Twig_Extensions_Extension_I18n());
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

 public function exceptionHandler($e) {
  $site_name = $this->api('setting/get', ['name' => 'site_name']);
  $code = $e->getCode();
  switch($code) {
   case 403:
   case 404:
    header("HTTP/1.0 ".$code);
    $_SERVER['REDIRECT_STATUS'] = $code;
    $this->addData([
     'number'	=>	$code,
     'error_image'	=>	$this->api('setting/get', ['name' => $code . '_image']),
     'rand'	=>	md5(uniqid(rand(), true))
    ]);
    $this->template('error');
    $this->output();
    die();
    break;
   default:
    $data = [
     'page'    => $this->browserdata->server('REQUEST_URI'),
     'code'    => $code,
     'message' => $e->getMessage()
    ];
    $message = new \JohnVanOrange\API\Mail();
    $message->sendAdminMessage('Error Occurred for '. $site_name, 'error', $data);
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

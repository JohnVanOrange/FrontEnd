<?php
namespace JohnVanOrange\jvo\Controller;

class API extends Base {

 public function process() {

	$public = new \JohnVanOrange\API\PublicAPI;

	$public->setClass($this->route->get_data(1));
	$public->setMethod($this->route->get_data(2));
	$public->setRequest(array_merge($_REQUEST, $_FILES));

	header ('Content-type: application/json; charset=UTF-8');
	header ('Access-Control-Allow-Origin: *');

	$expire_length = 60 * 60 * 4; //4 hours
	if ($this->route->get_data(2) == 'random')  $expire_length = 0; //don't cache calls to random
	header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + $expire_length ));

	echo $public->output();

 }

}

<?php
namespace JohnVanOrange\jvo\Controller;

class API extends Base {

 public function process() {

	$public = new \JohnVanOrange\API\PublicAPI;

	$public->setClass($this->route->get_data(1));
	$public->setMethod($this->route->get_data(2));
	$public->setRequest(array_merge($_REQUEST, $_FILES));

	$public->output();

 }

}

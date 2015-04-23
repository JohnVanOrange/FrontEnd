<?php
namespace JohnVanOrange\jvo;

class BrowserData {

	public function __call($method, $args) {
		return filter_input(constant('INPUT_' . strtoupper($method)), $args[0]);
	}

}

<?php
namespace JohnVanOrange\jvo\Controller;

class ChangePW extends Standard {

 public function process() {

  $this->setTemplate('changepw');

  $data = new \JohnVanOrange\jvo\BrowserData;

  $this->addData(['type' => 'sid']);
  $sid = $data->cookie('sid');
  if ($sid) $this->addData(['auth' => $sid]);

		$resetkey = $data->get('resetkey');
		if ($resetkey) {
			$this->addData([
				'auth'	=>	$resetkey,
				'type'	=>	'pwreset'
			]);
		}

 }

}

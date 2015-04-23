<?php
namespace JohnVanOrange\jvo\Controller;

class Docs extends Standard {

	public function process() {

		$class = $this->route->get_data(1);

		$this->setTemplate('docs');
		if (!$class) $this->setTemplate('docs_index');

		//Need to rework this to be auto-generated.
		$classes = [
			'tag',
			'image',
			'user',
			'report',
			'setting',
			'message'
		];

		$this->addData([
			'classes'	=>	$classes
		]);

		//does any of this need to happen if there is already no $class?
		$xml = file_get_contents('structure.xml');
		$docs = new \SimpleXMLElement($xml);
		$docs = json_decode(json_encode($docs), TRUE);

		foreach ($docs['file'] as $obj) {
			if (strtolower($obj['class']['name']) == $class) $this->addData(['class' => $this->process_docdata($obj['class'])]);
		}

	}

	private function process_docdata($data) {
		$output = [];
		//lowercase the class name
		$output['name'] = strtolower($data['name']);
		//Prcoess each method in the class
		$output['method'] = $this->process_methods($data['method']);
		return $output;
	}

	private function process_methods( $methods ) {
		$result = [];
		foreach ($methods as $method) {
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
			if (isset($method['api'])) $result[] = $method;
		}
		return $result;
	}

}
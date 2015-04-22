<?php
namespace JohnVanOrange\jvo\Controller;

class Cloud extends Standard {

 public function process() {

  $this->setTemplate('cloud');

  $tag_cloud = new Components\TagCloud;

  $this->addData([
		'tags'	=>	$tag_cloud->getData(),
		'title_text'	=>	'Tag Cloud'
  ]);

 }

}

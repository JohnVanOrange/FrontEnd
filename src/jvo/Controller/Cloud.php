<?php
namespace JohnVanOrange\jvo\Controller;

class Cloud extends Standard {
 
 public function process() {
  
  $this->setTemplate('cloud');
  
	$maxfont = '60';
	$minfont = '10';
	
	$tags = $this->api('tag/top');
	
	$max = current($tags);
	$min = end($tags);
	
	foreach ($tags as $i=>$tag)  {
	 if ($tag['count'] > $min['count']) {
		$tags[$i]['diff'] =  ($tag['count'] - $min['count']);
		$tags[$i]['weight'] = ceil((($maxfont - $minfont) * ($tag['count'] - $min['count'])) / ($max['count'] - $min['count'])) + $minfont;
	 }
	 else {
		$tags[$i]['weight'] = $minfont;
	 }
	}
	
	shuffle($tags);
	
	$this->addData([
		'tags'	=>	$tags,
		'title_text'	=>	'Tag Cloud'
	]);
	
 }
 
}
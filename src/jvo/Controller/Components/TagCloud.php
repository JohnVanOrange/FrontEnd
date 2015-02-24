<?php
namespace JohnVanOrange\jvo\Controller\Components;

class TagCloud {

 public function getData() {

    $api = new \JohnVanOrange\PublicAPI\API;

  	$maxfont = '60';
	$minfont = '10';

	$tags = $api->call('tag/top');

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

    return $tags;
 }

}

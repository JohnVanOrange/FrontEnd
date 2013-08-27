<?php
namespace JohnVanOrange\jvo;

require('twig.php');

$api = new API();

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

$data = [
	'tags'	=>	$tags,
	'title_text'	=>	'Tag Cloud'
];

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");

$template = $twig->loadTemplate('cloud.twig');
echo $template->render($data);
<?php
require('smarty.php');

$template = 'cloud.tpl';

$maxfont = '60';
$minfont = '10';

$tags = call('tag/top');

$max = current($tags);
$min = end($tags);

echo 'max: '.$max['count'];
echo '<br>';
echo 'min: '.$min['count'];

foreach ($tags as $i=>$tag)  {
 //$tags[$i]['weight'] = ceil((($maxfont * ($tag['count'] - $min['count']) / $max['count'] - $min['count']) + 2) * 10);
 if ($tag['count'] > $min['count']) {
  $tags[$i]['diff'] =  ($tag['count'] - $min['count']);
  $tags[$i]['weight'] = ceil($maxfont * ($tag['count'] - $min['count']) / $max['count'] - $min['count']) + $minfont;
 }
 else {
  $tags[$i]['weight'] = $minfont;
 }
 //$tags[$i]['diff'] =  ($tag['count'] - $min['count']);
}

print_r($tags);

shuffle($tags);
$tpl->assign('tags', $tags);

$tpl->assign('title_text','Tag Cloud');

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");
$tpl->display($template);

?>
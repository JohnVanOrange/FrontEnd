<?php
require('smarty.php');

$template = 'cloud.tpl';

$maxfont = '10';

$tags = call('tag/top');

$max = current($tags);
$min = end($tags);

foreach ($tags as $i=>$tag)  {
 $tags[$i]['weight'] = ceil((($maxfont * ($tag['count'] - $min['count']) / $max['count'] - $min['count']) + 2) * 10);
}

shuffle($tags);
$tpl->assign('tags', $tags);

$tpl->assign('title_text','Tag Cloud');

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");
$tpl->display($template);

?>
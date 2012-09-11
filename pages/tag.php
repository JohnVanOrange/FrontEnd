<?php
require('smarty.php');

$template = 'tag.tpl';

$tag_name = $request[1];

$images = call('tag/all',array('tag'=>$tag_name));
foreach ($images as $i) {
 $imagelist[] = call('image/get',array('image'=>$i['image']));
}
$tpl->assign('images',$imagelist);

$page_tag = call('tag/get',array('value'=>$tag_name,'search_by'=>'basename'));
$tpl->assign('tag',$page_tag[0]);

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");
$tpl->display($template);
?>
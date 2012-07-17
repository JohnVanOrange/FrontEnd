<?php
require('smarty.php');
require_once('classes/tag.class.php');
require_once('classes/image.class.php');

$tag = new Tag;
$image = new Image;

$template = 'tag.tpl';

$images = $tag->all(array('tag'=>$tag_name));
foreach ($images as $i) {
 $imagelist[] = $image->get(array('image'=>$i['image']));
}
$tpl->assign('images',$imagelist);

$page_tag = $tag->get(array('value'=>$tag_name,'search_by'=>'basename'));
$tpl->assign('tag',$page_tag[0]);

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");
$tpl->display($template);
?>
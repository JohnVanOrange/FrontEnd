<?php
require_once('smarty.php');

$template = 'display.tpl';

$image_name = $request[1];

$result = call('image/get',array('image'=>$image_name));

$tpl->assign('image', $result['image_url']);
$tpl->assign('image_name', $result['filename']);
$tpl->assign('image_id',$result['id']);
$tpl->assign('uid',$result['uid']);
$tpl->assign('type',$result['type']);
$tpl->assign('width',$result['width']);
$tpl->assign('height',$result['height']);
$tpl->assign('rand',md5(uniqid(rand(), true)));
$tpl->assign('report_types',call('report/all'));
$tpl->assign('title_text', $result['uid']);
if ($result['tags']) {
 $tpl->assign('tags',$result['tags']);
 $title_text = '';
 foreach ($result['tags'] as $tag) {
  $title_text .= $tag['name'] . ', ';
 }
 $tpl->assign('title_text', rtrim($title_text, ', '));
}
if ($result['data']) $tpl->assign('data',$result['data']);
if ($result['saved']) $tpl->assign('saved', TRUE);
if ($result['c_link']) $tpl->assign('c_link', $result['c_link']);
if ($result['uploader']) $tpl->assign('uploader', $result['uploader']);

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");
$tpl->display($template);
?>

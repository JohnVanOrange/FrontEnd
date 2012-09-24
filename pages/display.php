<?php
require_once('smarty.php');

$template = 'display.tpl';

switch($request[0]) {
 case 'b':
 case 'brazzify':
  $brazzify = TRUE;
 break;
 case 'n':
  $new = TRUE;
 break;
}

$image_name = $request[1];

$result = call('image/get',array('image'=>$image_name));

$url = parse_url(WEB_ROOT);
$tag_basename = rtrim(str_replace($url['host'], '', $_SERVER['HTTP_HOST']),'.');
if ($tag_basename) {
 $page_tag = call('tag/get',array('value'=>$tag_basename,'search_by'=>'basename'));
 $tpl->assign('tag_name',$page_tag[0]['name']);
}

$tpl->assign('image', WEB_ROOT.'media/'.$result['filename']);
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
if ($result['c_link']) $tpl->assign('c_link', $result['c_link']);
if ($new) {
 $tpl->assign('page','new');
}
else {
 $tpl->assign('page','random');
}




$tpl->assign('brazzify',FALSE);
if (isset($brazzify)) $tpl->assign('brazzify',TRUE);

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");
$tpl->display($template);
?>

<?php
require_once('smarty.php');
require_once('classes/image.class.php');

$image = new Image;

$template = 'display.tpl';

try {
 $result = $image->get(array('image'=>$image_name));
}
catch (exception $e) {
 page_exception_handler($e);
}

$url = parse_url(WEB_ROOT);
$tag_basename = rtrim(str_replace($url['host'], '', $_SERVER['HTTP_HOST']),'.');
if ($tag_basename) {
 require_once('classes/tag.class.php');
 $tag = new Tag;
 $page_tag = $tag->get(array('value'=>$tag_basename,'search_by'=>'basename'));
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
if ($result['tags']) $tpl->assign('tags',$result['tags']);
if ($result['c_link']) $tpl->assign('c_link', $result['c_link']);
if (defined('DISQUS_SHORTNAME')) $tpl->assign('disqus_shortname', DISQUS_SHORTNAME);

$tpl->assign('brazzify',FALSE);
if (isset($brazzify)) $tpl->assign('brazzify',TRUE);

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");
$tpl->display($template);
?>

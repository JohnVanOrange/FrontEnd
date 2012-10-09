<?php
require('smarty.php');

$template = 'tag.tpl';

$tag_name = $request[1];

$tpl->assign('images', call('tag/all',array('tag'=>$tag_name)));

$page_tag = call('tag/get',array('value'=>$tag_name,'search_by'=>'basename'));
$tpl->assign('tag',$page_tag[0]);

$tpl->assign('title_text','Images tagged ' . $page_tag[0]['name']);

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");
$tpl->display($template);
?>
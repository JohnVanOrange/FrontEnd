<?php
require('smarty.php');
require_once('classes/tag.class.php');

$tag = new Tag;

$template = 'tag.tpl';

$tpl->assign('images',$tag->all(array('tag'=>$tag_name)));

require_once('common.php');

header("Content-type: text/html; charset=UTF-8");
$tpl->display($template);
?>
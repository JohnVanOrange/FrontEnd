<?php
namespace JohnVanOrange\jvo;

$iface = new SiteInterface\Standard;

$tag_name = $route->get_data(0);

$page_tag = $iface->api('tag/get', ['value'=>$tag_name, 'search_by'=>'basename']);

$iface->addData([
	'images'	=>	$iface->api('tag/all', ['tag'=>$tag_name]),
	'title_text'	=>	_('Images tagged') . ' ' . $page_tag[0]['name']
]);

$iface->template('thumbs');
echo $iface->render();
?>
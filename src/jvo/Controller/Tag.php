<?php
namespace JohnVanOrange\jvo\Controller;

class Tag extends Standard {
 
 public function process() {
  
  $this->setTemplate('thumbs');
	
	$tag_name = $this->route->get_data(1);
	
	$page_tag = $this->api('tag/get', ['value'=>$tag_name, 'search_by'=>'basename']);
	
	$this->addData([
		'images'	=>	$this->api('tag/all', ['tag'=>$tag_name]),
		'title_text'	=>	_('Images tagged') . ' ' . $page_tag[0]['name']
	]);
	
 }
 
}
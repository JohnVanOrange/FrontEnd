<?php
require_once(ROOT_DIR.'/classes/base.class.php');

class Tag extends Base {
 
 public function __construct() {
  parent::__construct();
 }
 
 public function add($options=array()) {
  $tags = explode(',',$options['name']);
  foreach ($tags as $tag) {
   $tag = htmlspecialchars(trim($tag));
   $slug = $this->text2slug($tag);
   $sql = 'SELECT id from tag_list WHERE basename = :name;';
   $val = array(
    ':name' => $slug
   );
   $result = $this->db->fetch($sql,$val);
   $tag_id = $result[0]['id'];
   if(!$tag_id) {
    $tag_id = $this->addtoList($tag);
   }
   $sql = 'INSERT INTO resources (image, value, type) VALUES(:image, :tag_id, "tag")';
   $val = array(
    ':image' => $options['image'],
    ':tag_id' => $tag_id
   );
   $this->db->fetch($sql,$val);
  }
  switch (count($tags)) {
   case 1:
    $return['message'] = 'Tag added';
   break;
   default:
    $return['message'] = 'Tags added';
   break;
  }
  $return['tags'] = $this->get(array('value'=>$options['image']));
  return $return;
 }
 
 public function get($options=array()) {
  switch ($options['search_by']) {
   case 'name':
   $options['search_by'] = 'basename';
   $options['value'] = $this->text2slug($options['value']);
   case 'basename':
   case 'image':
   break;
   default:
    $options['search_by'] = 'image';
   break;
  }
  $sql = 'SELECT l.name, basename, uid FROM resources r INNER JOIN tag_list l ON l.id = r.value INNER JOIN images i ON i.uid = r.image WHERE ('.$options['search_by'].' = :value AND r.type = "tag")';
  $val = array(
   ':value' => $options['value']
  );
  $results = $this->db->fetch($sql,$val);
  foreach ($results as $i => $r) {
   $url = parse_url(WEB_ROOT);
   $results[$i]['random_url'] = $url['scheme'].'://'.$r['basename'].'.'.$url['host'];
   $results[$i]['url'] = '/t/'.$r['basename'];
  }
  return $results;
 }
 
 public function all($options=array()) {
  $images = $this->get(array('value'=>$options['tag'],'search_by'=>'basename'));
  $results = array();
  foreach($images as $image) {
   $results[] = array('image'=>$image['uid']);
  }
  return $results;
 }

 public function suggest($options=array()) {
  $sql = "SELECT name FROM tag_list WHERE name LIKE :name LIMIT 10;";
  $val = array(
   ':name' => '%'.$options['term'].'%'
  );
  $results = $this->db->fetch($sql,$val);
  foreach ($results as $r) {
   $return[] = stripslashes($r['name']);
  }
  return $return;
 }
 
 private function addtoList($name) {
  $basename = $this->text2slug($name);
  $sql = 'SELECT id FROM tag_list WHERE basename = :basename';
  $val = array(
   ':basename' => $basename
  );
  $result = $this->db->fetch($sql,$val);
  if ($result) return $result[0]['id'];  
  $sql = 'INSERT INTO tag_list (name,basename) VALUES(:name,:basename);';
  $val = array(
   ':name' => $name,
   ':basename' => $basename
  );
  $s = $this->db->prepare($sql);
  $s->execute($val);
  return $this->db->lastInsertId();
 }
}
?>

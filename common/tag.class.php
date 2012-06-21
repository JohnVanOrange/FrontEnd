<?php
require_once(ROOT_DIR.'/common/base.class.php');

class Tag extends Base {
 
 public function __construct() {
  parent::__construct();
 }
 
 public function add($options=array()) {
  $tags = explode(',',$options['name']);
  foreach ($tags as $tag) {
   $tag = htmlspecialchars(trim($tag));
   $sql = 'SELECT id from tag_list WHERE name = :name;';
   $val = array(
    ':name' => $tag
   );
   $result = $this->db->fetch($sql,$val);
   $tag_id = $result[0]['id'];
   if(!$tag_id) {
    $tag_id = $this->addtoList($tag);
   }
   $sql = 'SELECT * from images WHERE uid = :image LIMIT 1;';
   $val = array(
    ':image' => $options['image']
   );
   $image = $this->db->fetch($sql,$val);
   $image = $image[0];
   $sql = 'INSERT INTO tags (image_id, tag_id) VALUES(:image_id, :tag_id);';
   $val = array(
    ':image_id' => $image['id'],
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
  $return['tags'] = $this->get(array('image_id'=>$image['id']));
  return $return;
 }
 
 public function get($options=array()) {
  $sql = 'SELECT name FROM tags t INNER JOIN tag_list l ON l.id = t.tag_id WHERE image_id = :image_id;';
  $val = array(
   ':image_id' => $options['image_id']
  );
  $results = $this->db->fetch($sql,$val);
  $tags = array();
  foreach ($results as $r) {
   $tags[] = stripslashes($r['name']);
  }
  return $tags;
 }
 
 public function suggest($options=array()) {
  $sql = "SELECT name FROM tag_list WHERE name LIKE :name LIMIT 10;";
  $val = array(
   ':name' => '%'.$options['term'].'%'
  );
  $results = $this->db->fetch($sql,$val);
  foreach ($results as $r) {
   $return[] = $r['name'];
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

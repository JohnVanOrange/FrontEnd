<?php
namespace JohnVanOrange\jvo;

class Tag extends Base {
 
 private $res;
 
 public function __construct() {
  parent::__construct();
  $this->res = new Resource;
 }
 
 /**
  * Add tag
  *
  * Add tags to images
  *
  * @api
  * 
  * @param string $name The tag value to be added to the image. Multiple tags can be added if comma separated.
  * @param string $image 6-digit image ID.
  */
 
 public function add($name, $image) {
  $tags = explode(',',$name);
  foreach ($tags as $tag) {
   $tag = htmlspecialchars(trim(stripslashes($tag)));
   $slug = $this->text2slug($tag);
   $sql = 'SELECT id from tag_list WHERE basename = :name;';
   $val = array(
    ':name' => $slug
   );
   $result = $this->db->fetch($sql,$val);
   $tag_id = NULL;
   if (isset($result[0]['id'])) $tag_id = $result[0]['id'];
   if(!isset($tag_id)) {
    $tag_id = $this->addtoList($tag);
   }
   $val = array(
    ':image' => $image,
    ':tag_id' => $tag_id
   );
   //check for dupe
   $sql = 'SELECT * FROM resources WHERE (image = :image AND value = :tag_id AND type = "tag")';
   $result = $this->db->fetch($sql,$val);
   if ($result) throw new \Exception('Tag already exists');
   $this->res->add('tag', $image, $tag_id);
  }
  switch (count($tags)) {
   case 1:
    $return['message'] = 'Tag added';
   break;
   default:
    $return['message'] = 'Tags added';
   break;
  }
  $return['tags'] = $this->get($image);
  return $return;
 }
 
 /**
  * Get tags
  *
  * List the tags for an image
  *
  * @api
  * 
  * @param string $value The 6-digit UID for an image. Also can accept a tag basename if the search_by parameter is set to basename.
  * @param string $search_by Defaults to "image". Other possible value is "basename".
  */
 
 public function get($value, $search_by='image') {
  switch ($search_by) {
   case 'name':
    $search_by = 'basename';
    $value = $this->text2slug($value);
   case 'basename':
   case 'image':
    break;
   default:
    $search_by = 'image';
  break;
  }
  $sql = 'SELECT l.name, basename, uid FROM resources r INNER JOIN tag_list l ON l.id = r.value INNER JOIN images i ON i.uid = r.image WHERE ('.$search_by.' = :value AND r.type = "tag")';
  $val = array(
   ':value' => $value
  );
  $results = $this->db->fetch($sql,$val);
  foreach ($results as $i => $r) {
   $url = parse_url(WEB_ROOT);
   $results[$i]['url'] = '/t/'.$r['basename'];
  }
  return $results;
 }
 
 /**
  * All images with tag
  *
  * Get all images that have a certain tag.
  *
  * @api
  * 
  * @param string $tag The basename value for a particular tag.
  */
 
 public function all($tag) {
  $i = $this->image = new Image;
  $images = $this->get($tag, 'basename');
  $results = array();
  foreach($images as $image) {
   try {
    $results[] = $i->get($image['uid']);
   }
   catch(\Exception $e) {
    if ($e->getCode() != 403) {
     throw new \Exception($e);
    }
   }
  }
  return $results;
 }

 /**
  * Tag suggest
  * 
  * This is used to find suggested tags by giving it parts of text, and it will return the 10 closest matches for existing tags.
  *
  * @api
  * 
  * @param string $term Partial text that is used to query for existing tag names.
  */
 
 public function suggest($term) {
  $sql = "SELECT name FROM tag_list WHERE name LIKE :name LIMIT 10;";
  $val = array(
   ':name' => '%'.$term.'%'
  );
  $results = $this->db->fetch($sql,$val);
  foreach ($results as $r) {
   $return[] = stripslashes($r['name']);
  }
  return $return;
 }
 
 /**
  * Top tags
  * 
  * Returns the top 200 used tags and a count of the number of times they are used.
  *
  * @api
  */
 
 public function top() {
  $sql = 'SELECT t.name, t.basename, COUNT(*) AS count FROM resources r INNER JOIN tag_list t ON t.id = r.value WHERE r.type = "tag" GROUP BY r.value ORDER BY COUNT(*) DESC LIMIT 200;';
  $results = $this->db->fetch($sql);
  return $results;
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
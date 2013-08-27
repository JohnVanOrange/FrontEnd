<?php
namespace JohnVanOrange\jvo;

require_once('../../vendor/autoload.php');
require_once('../../settings.inc');

require_once('simple_html_dom.php');

class remote extends Base {
 public function fetch($url) {
  $ch = curl_init();
  $cookie = tempnam ("/tmp", "COOKIE");
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
  curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_4) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.54 Safari/536.5');//google snifs the UA
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
  curl_setopt($ch, CURLOPT_MAXREDIRS, 8);
  curl_setopt($ch, CURLOPT_TIMEOUT, 30);
  $response = curl_exec($ch);
  curl_close($ch);
  return $response;
 }
}

$db = new DB('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
$image = new Image;
$tag = new Tag;
$remote = new remote;
$query = new \Peyote\Select('images');
$query->columns('filename, uid')
      ->where('display', '=', 1)
      ->orderBy('RAND()')
      ->limit('50000');
$results = $db->fetch($query);
$counter = 0;
$no_result_counter = 0;
$tag_added = 0;
foreach ($results as $result) {
 $counter++;
 $imagedata = $image->get($result['uid']);
 if (!isset($imagedata['tags'][0])) {
  $no_result_counter++;
  $html = str_get_html($remote->fetch('http://www.google.com/searchbyimage?image_url='.$imagedata['image_url']));
  if (array_search('debug', $argv) !== FALSE) echo $html;
  $data = $html->find('a[style]',0);
  $taginfo = @$data->nodes[0]->_[4];
  $html->clear();
  if (isset($taginfo)) {
   $tag->add($taginfo,$result['uid']);
   echo 'Tag added for '.$result['uid'].': '.$taginfo."\n";
   $tag_added++;
  }
  else {
   echo 'No tag found for ' . $result['uid'] . "\n";
  }
 }
}
echo "\n\n";
echo "Process complete!\n";
echo $counter . " total images, " . $no_result_counter . ' untagged images, ' . $tag_added . " tags added\n";

?>

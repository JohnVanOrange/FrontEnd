<?
require_once('../settings.inc');
require_once(ROOT_DIR.'/classes/base.class.php');

class remote extends Base {
 public function fetch($url) {
  return $this->remoteFetch(array('url'=>$url));
 }
}

$r = new Remote;

$sr = file_get_contents('subreddits');
$srlist = explode("\n",$sr);

foreach ($srlist as $sr) {
 if ($sr) print_r(json_decode($r->fetch(WEB_ROOT.'api/reddit/process?subreddit='.$sr)));
}

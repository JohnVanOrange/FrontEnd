<?
require_once('../vendor/autoload.php');
require_once('../settings.inc');

class remote extends JohnVanOrange\jvo\Base {
 public function fetch($url) {
  return $this->remoteFetch($url);
 }
}

$r = new Remote;

$sr = file_get_contents('subreddits');
$srlist = explode("\n",$sr);

$setting = new JohnVanOrange\jvo\Setting;

foreach ($srlist as $sr) {
 if ($sr) print_r(json_decode($r->fetch($setting->get('web_root') . 'api/reddit/process?subreddit=' . $sr)));
}
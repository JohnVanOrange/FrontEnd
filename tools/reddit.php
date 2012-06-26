<?
require_once('../settings.inc');
require_once(ROOT_DIR.'/common/reddit.class.php');

$r = new Reddit;

$sr = file_get_contents('subreddits');
$srlist = explode("\n",$sr);

foreach ($srlist as $sr) {
 if ($sr) $r->process($sr);
}

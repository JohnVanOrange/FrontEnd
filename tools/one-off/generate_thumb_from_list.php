<?php
namespace JohnVanOrange\jvo;

require_once('../../vendor/autoload.php');
require_once('../../settings.inc');

$image = new Image;
$media = new Media;

$thumblist = explode("\n", file_get_contents(ROOT_DIR.'/tools/one-off/missing_thumbs'));

foreach ($thumblist as $uid) {
  $i = $image->get($uid);
  $thumb = $image->scale($uid);
  file_put_contents(ROOT_DIR.'/media/thumbs/'.$i['filename'],$thumb);
  $media->add($uid, '/media/thumbs/' . $i['filename'], 'thumb');
}
<?php
require_once('settings.inc');
require_once('common/exceptions.php');
require_once('classes/media.class.php');

$media = new Media;

$full_request = $_SERVER['REQUEST_URI'];
$request = explode('?',$full_request);
$request = explode('/',trim($request[0],'/'));

if (strpos($_SERVER['HTTP_REFERER'],WEB_ROOT) === FALSE && defined('WATERMARK')) {
 $image = call('media/watermark', array('image' => $request[1]));
}
else {
 $image = call('media/get', array('image' => $request[1]));
}

header('Content-type: '.$image->getImageMimeType());
echo $image->getImagesBlob();
<?php
// Created 12/13/14
namespace JohnVanOrange\jvo;

require_once('../vendor/autoload.php');
require_once('../settings.inc');
$db = new \JohnVanOrange\core\DB('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);

if (!$argv[1]) {
 echo "Need to supply a count argument!\n";
 exit;
}

$count = $argv[1];
$counter = 0;

$query = new \Peyote\Select('storage');
$query->where('type', '=', 's3')
      ->limit(1);
$s3data = $db->fetch($query)[0];

$s3 = new \S3($s3data['access_key'], $s3data['secret_key'], false, $s3data['endpoint']);

echo "Moving images to " . $s3data['endpoint'] . " into the " . $s3data['bucket'] . " bucket\n\n";

$query = new \Peyote\Select('media');
$query->columns('media.uid',
                'media.file',
                'storage.type')
      ->join('storage', 'inner')
      ->on('storage.id', '=', 'media.storage')
      ->where('storage.type', '=', 'local')
      ->where('media.type', '=', 'primary')
      ->limit($count);

$results = $db->fetch($query);

foreach ($results as $result) {
 $filename = explode('/',  $result['file'])[2];
 $localimage = '..' . $result['file'];
 //upload the image
 $s3->putObject(\S3::inputFile($localimage), $s3data['bucket'], $filename, \S3::ACL_PUBLIC_READ);
 //verify image was uploaded
 $verify = null;
 $verify = $s3->getObject($s3data['bucket'], $filename);
 if ($verify->headers['hash'] == md5_file($localimage)) {
  //update database entry
  $query = new \Peyote\Update('media');
  $update = [
   'file' => $filename,
   'storage' => $s3data['id']
  ];
  $query->set($update)
        ->where('uid', '=', $result['uid'])
        ->where('type', '=', 'primary');
  $db->fetch($query);
  //remove local file
  unlink($localimage);
  echo $result['uid'] . " moved successfully\n";
  $counter++;
 }
 else {
  //upload failed
  echo "There was a failure uploading image ". $result['uid'] . "\n";
 }
}

echo "\n\n";
echo "Process complete!\n";
echo $counter . " total images moved \n";

?>
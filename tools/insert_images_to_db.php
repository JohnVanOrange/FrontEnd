<?php
require_once('../settings.inc');
require_once(ROOT_DIR.'/common/db.php');

$dir = ROOT_DIR.'/media/';
$files = glob($dir.'*.*');

$sql = "INSERT INTO images(filename, hash, type, width, height) VALUES(:filename, :hash, :type, :width, :height)";

foreach ($files as $file) {
 echo "Importing ".$file."\n";
 $filename = end(explode('/',$file));
 $info = getimagesize($file);
 $width = $info[0];
 $height = $info[1];
 $type = end(explode('/',$info['mime']));
 $hash = md5_file($file);

 $val = array(
  ':filename' => $filename,
  ':hash' => $hash,
  ':type' => $type,
  ':width' => $width,
  ':height' => $height,
 );
 $s = $db->prepare($sql);
 $s->execute($val);
}

?>

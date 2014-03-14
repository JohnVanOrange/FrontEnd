<?php
namespace JohnVanOrange\jvo;

require_once('../vendor/autoload.php');
require_once('../settings.inc');

$image = new Image;

$start = microtime(TRUE);

for ($i = 1; $i <= 10000; $i++) {
 @$image->random();
 echo '.';
}

$end = microtime(TRUE);

$time = $end - $start;

echo "\nCompleted in " . number_format($time, 3) . " seconds.\n";
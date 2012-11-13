<?php
if ($request[0] == 'm') setcookie('mobile', 'y',time()+60*60*24*365,'/');
if ($request[0] == 'f') setcookie('mobile', 'n',time()+60*60*24*365,'/');

$image = call('image/random');

header('Location: '. $image['page_url']);
?>

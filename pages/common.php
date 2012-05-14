<?php

$tpl->assign('ga',GOOGLE_ANALYTICS);
$tpl->assign('site_name',SITE_NAME);

if ($_COOKIE['theme']) {
 $tpl->assign('theme',$_COOKIE['theme']);
}
else {
 $tpl->assign('theme','light');
}
?>

<?php

$tpl->assign('ga',GOOGLE_ANALYTICS);

if ($_COOKIE['theme']) {
 $tpl->assign('theme',$_COOKIE['theme']);
}
else {
 $tpl->assign('theme','light');
}
?>

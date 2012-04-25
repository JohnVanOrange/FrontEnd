<?php
require_once(ROOT_DIR.'/common/db.class.php');
$db = new DB('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
?>

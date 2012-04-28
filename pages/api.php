<?php
require_once('common/api.class.php');

$api = new API;

$result = $api->{$method}($_POST);
echo json_encode($result);
?>

<?php
require_once('common/api.class.php');

$api = new API;

try {
 $result = $api->{$method}($_POST);
}
catch (exception $e) {
 js_exception_handler($e);
}

echo json_encode($result);
?>

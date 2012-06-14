<?php
require_once('common/image.class.php');

$image = new Image;

header ('Content-type: application/json; charset=UTF-8');

try {
 $result = $image->{$method}($_REQUEST);
}
catch (exception $e) {
 js_exception_handler($e);
}

echo json_encode($result);
?>

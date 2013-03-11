<?php
$image = call('image/random');

header('Location: '. $image['page_url']);
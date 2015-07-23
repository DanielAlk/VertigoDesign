<?php
include '../environment/main.php';
if (isset($page_headers)) foreach ($page_headers as $header) header($header);
include $app->layout;
$app->buffer();
?>
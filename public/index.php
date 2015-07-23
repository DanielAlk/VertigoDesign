<?php
include '../environment/main.php';
if (isset($page_headers)) foreach ($page_headers as $header) header($header);
include $app->layout;
if (isset($app->buffer)) $app->buffer();
?>
<?php
include '../environment/init.php';
if (isset($page_headers)) foreach ($page_headers as $header) header($header);
include $app->layout;
if (method_exists($app, 'buffer')) $app->buffer();
?>
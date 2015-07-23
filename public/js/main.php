<?php
use MatthiasMullie\Minify;
$config = parse_ini_file('../../config/env.ini', true);
$settings = parse_ini_file('../../config/app.ini', true);
$minify_active = $config['minify_assets'];
if ($minify_active) ob_start();
$cache_control = $settings['cache-control']['js'];
header('Cache-Control: '.$cache_control);
header("Content-type: application/javascript; charset: UTF-8");
set_include_path('../../app/assets/javascripts/');
include $_GET['asset'].'.php';

if ($minify_active) {
	set_include_path('../../extensions/minify/src/');
	include 'Converter.php';
	include 'Minify.php';
	include 'JS.php';
	include 'Exception.php';
	$minifier = new Minify\JS(ob_get_contents());
	ob_end_clean();
	echo $minifier->minify();
}
?>
<?php
use MatthiasMullie\Minify;
$requested_asset = $_GET['asset'];
$config = parse_ini_file('../../config/env.ini', true);
$settings = parse_ini_file('../../config/app.ini', true);
$minify_active = $config['minify_assets'];
$cache_control = $settings['cache-control']['js'];
header('Cache-Control: '.$cache_control);
header("Content-type: application/javascript; charset: UTF-8");
set_include_path('../../app/assets/javascripts/');


if ($minify_active && !file_exists($requested_asset.'.js')) {
	ob_start();
	include $requested_asset.'.php';
	set_include_path('../../extensions/minify/src/');
	include 'Converter.php';
	include 'Minify.php';
	include 'JS.php';
	include 'Exception.php';
	$minifier = new Minify\JS(ob_get_contents());
	ob_end_clean();
	file_put_contents($requested_asset.'.js', $minifier->minify());
	include __DIR__.'/'.$requested_asset.'.js';
}
else if ($minify_active) {
	include __DIR__.'/'.$requested_asset.'.js';
} else {
	include $requested_asset.'.php';
}
?>
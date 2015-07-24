<?php
use MatthiasMullie\Minify;
$requested_asset = $_GET['asset'];
set_include_path('../../app/');
include '../../environment/asset.php';
$config = parse_ini_file('../../config/env.ini', true);
$minify_active = $config['minify_assets'];
$base_url = $config['base_url'];
$asset = new asset();
$settings = parse_ini_file('../../config/app.ini', true);
$cache_control = $settings['cache-control']['css'];
header('Cache-Control: '.$cache_control);
header("Content-type: text/css; charset: UTF-8");
set_include_path('../../app/assets/stylesheets/');

if ($minify_active && !file_exists($requested_asset.'.css')) {
	ob_start();
	include $requested_asset.'.php';
	set_include_path('../../extensions/minify/src/');
	include 'Converter.php';
	include 'Minify.php';
	include 'CSS.php';
	include 'Exception.php';
	$minifier = new Minify\CSS(ob_get_contents());
	ob_end_clean();
	file_put_contents($requested_asset.'.css', $minifier->minify());
	include __DIR__.'/'.$requested_asset.'.css';
}
else if ($minify_active) {
	include __DIR__.'/'.$requested_asset.'.css';
}
else {
	include $requested_asset.'.php';
}
?>
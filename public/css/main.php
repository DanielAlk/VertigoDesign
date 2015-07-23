<?php
use MatthiasMullie\Minify;
set_include_path('../../app/');
include '../../environment/asset.php';
$config = parse_ini_file('../../config/env.ini', true);
$env = $config['environment'];
$minify_active = $config[$env]['minify_assets'];
if ($minify_active) ob_start();
$base_url = $config[$env]['base_url'];
$asset = new asset();
$settings = parse_ini_file('../../config/app.ini', true);
$cache_control = $settings['cache-control']['css'];
header('Cache-Control: '.$cache_control);
header("Content-type: text/css; charset: UTF-8");
set_include_path('../../app/assets/stylesheets/');
include $_GET['asset'].'.php';

if ($minify_active) {
	set_include_path('../../extensions/minify/src/');
	include 'Converter.php';
	include 'Minify.php';
	include 'CSS.php';
	include 'Exception.php';
	$minifier = new Minify\CSS(ob_get_contents());
	ob_end_clean();
	echo $minifier->minify();
}
?>
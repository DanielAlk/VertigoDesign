<?php
class Environment {
	public function __construct($load_database = false) {
		set_include_path('../app/');
		$app_dir = get_include_path();
		//READ CONFIGURATION AND SETTINGS
		$settings = parse_ini_file('../config/env.ini', true);
		$database = $load_database ? $settings['database'] : false;
		//SET GLOBALS
		$GLOBALS['environment'] = $settings['environment'];
		$GLOBALS['host'] = $settings['host'];
		$GLOBALS['base_url'] = $settings['base_url'];
		$GLOBALS['debug'] = $settings['debug'] === '1';
		//WHEN CALLING THIS CLASS, GLOBAL VARIABLE params IS CREATED
		require '../environment/path.php';
		$GLOBALS['path'] = new Path;
		global $params;
		$controller = isset($params['controller']) ? $params['controller'].'_controller' : false;
		$action = isset($params['action']) ? $params['action'] : false;
		//DIE IF CONTROLLER OR ACTION NOT SETTED
		if (!$controller || !$action) return $this->not_found();
		//DIE IF CONTROLLER NOT FOUND
		$controller_file = 'controllers/'.$controller.'.php';
		if (!file_exists($app_dir.$controller_file)) return $this->not_found();
		//ALL GOOD, CONTINUE
		//GET AND SET ENVIRONMENT CLASSES
		require '../environment/app.php';
		require '../environment/base.php';
		require '../environment/locale.php';
		require '../environment/asset.php';
		require '../environment/mailer.php';
		$GLOBALS['locale'] = new Locale;
		$GLOBALS['asset'] = new Asset;
		$GLOBALS['mailer'] = new Mailer($settings['mailer']);
		//GET AND SET PLUGINS
		$dir = $app_dir."plugins/";
		foreach (glob($dir."*.php") as $filename) {
			require $filename;
			$class_name = str_replace(array($dir,'.php'), '', $filename);
			$GLOBALS[$class_name] = new $class_name;
		}
		//GET AND SET MODELS
		$dir = $app_dir."models/";
		foreach (glob($dir."*.php") as $filename) {
			require $filename;
			$class_name = str_replace(array($dir,'.php'), '', $filename);
			$GLOBALS[$class_name] = new $class_name($database);
		}
		//GET AND SET HELPERS
		$dir = $app_dir."helpers/";
		foreach (glob($dir."*.php") as $filename) {
			require $filename;
			$class_name = str_replace(array($dir,'.php'), '', $filename);
			$var_name = str_replace('_helper', '', $class_name);
			$GLOBALS[$var_name] = new $class_name;
		}
		//GET AND SET CONTROLLERS
		require 'controllers/application_controller.php';
		require $controller_file;
		$GLOBALS['app'] = new $controller;
	}

	private function not_found() {
		header("HTTP/1.0 404 Not Found");
		$GLOBALS['app'] = new StdClass;
		$GLOBALS['app']->layout = 'views/layouts/not_found.php';
		return false;
	}
	
}
?>
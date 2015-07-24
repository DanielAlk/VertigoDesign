<?php
class Path {

	private $routes;
	private $paths;

	# TODO: make this function recognize more than one pattern defined in routes.ini
	public function __construct() {
		$this->routes = $routes = parse_ini_file('../config/routes.ini');
		$path = $_GET['path'];
		unset($_GET['path']);
		$params = array();
		if (in_array($path, $routes)) {
			$params = $this->params_from_key(array_search($path, $routes), $params);
		}
		else foreach($routes as $route) {
			if (preg_match_all('/{\w+}\//', $route, $results)) {
				foreach ($results as $r) {
					$cleaned_route = str_replace($r, '', $route);
				}
				$route_subs = explode('/', $route);
				$clean_subs = explode('/', $cleaned_route);
				$path_subs = explode('/', $path);
				$is_match = false;
				if (count($path_subs) == count($route_subs)) {
					for($i = 0; $i<count($clean_subs); $i++) {
						if ($clean_subs[$i] == $path_subs[$i]) $is_match = true;
						else if ($clean_subs[$i] != '') {
							$is_match = false;
							break;
						}
					}
					if ($is_match) for ($i = 0; $i<count($route_subs); $i++) {
						if (preg_match('/{\w+}/', $route_subs[$i])) {
							$param_name = str_replace(array('{','}'), '', $route_subs[$i]);
							$params[$param_name] = $path_subs[$i];
							$params = $this->params_from_key(array_search($route, $routes), $params);
						}
					}
				}
			}
		}
		$this->create_path_methods();
		$GLOBALS['params'] = $params;
	}

	private function params_from_key($key, $params = array()) {
		if(strpos($key, '#') !== false) list($controller, $action) = explode('#', $key);
		else { $controller = $key; $action = 'index'; };
		$params['controller'] = $controller;
		$params['action'] = $action;
		return $params;
	}

	private function create_path_methods() {
		foreach ($this->routes as $key => $value) {
			$params = $this->params_from_key($key);
			if (strpos($key, '#') !== false) $key = str_replace('#', '_', $key);
			$this->paths[$key] = array('controller' => $params['controller'], 'action' => $params['action'], 'route' => $value);
		}
	}

}
?>
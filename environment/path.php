<?php
class Path {

	# TODO: make this function recognize more than one pattern defined in routes.ini
	public function __construct() {
		$routes = parse_ini_file('../config/routes.ini');
		foreach ($routes as $key => $value) $this->$key = $value;
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
		$GLOBALS['params'] = $params;
	}

	private function params_from_key($key, $params) {
		if(strpos($key, '#') !== false) list($controller, $action) = explode('#', $key);
		else {
			$controller = $key;
			$action = 'index';
		};
		$params['controller'] = $controller;
		$params['action'] = $action;
		return $params;
	}

	public function __call($name, $args) {
		$absolute_path = $name[0] == '_';
		if ($absolute_path) $name = substr($name, 1);
		if (isset($args) && isset($args[0])) {
			if (is_object($args[0]) || is_array($args[0])) $arr = (array)$args[0];
			else if (is_string($args[0])) $arr = $args[0];
		}
		
		if (isset($this->$name)) {
			$route = $this->$name;
			if (isset($arr) && is_array($arr)) {
				//SI EN LA URL DEFINIDA FIGURA ALGO TIPO: {id} , ESO SERA REEMPLAZADO POR $arr['id'];
				if (preg_match_all('/{\w+}/', $route, $results)) {
					$prop_names = $props = array();
					foreach($results as $result) foreach(preg_replace('/[{}]/', '', $result) as $p) $prop_names[] = $p;
					foreach ($prop_names as $key => $prop_name) if (isset($arr[$prop_name])) {
						$prop = $arr[$prop_name];
						unset($arr[$prop_name]);
						$props[] = $prop;
					}
					if (count($props)) {
						$route = str_replace($prop_names, $props, $route);
						$route = str_replace(array('{','}'), '', $route);
					}
					//SI PROPIEDADES DEL ARRAY NO FUERON REEMPLAZADAS SE CONVIERTEN A QUERY
					if (count($arr)) {
						$route.='?'.http_build_query($arr);
					}
				}
				//SI EN LA URL NO HAY NADA QUE REEMPLAZAR EL ARRAY SE CONVIERTE A QUERY
				else $route.='?'.http_build_query($arr);
			}
		}
		else if(strpos($name, '_')) {
			list($controller, $action) = explode('__', $name);
			$route = $controller.'/'.$action.'/';
		}
		else {
			$route = $name.'/';
		}

		if (isset($arr)) {
			if (!isset($this->$name) && is_array($arr)) $route.='?'.http_build_query($arr);
			else if (is_string($arr)) $route.=$arr;
		}

		//CLEAN ROUTE IF PATTERN STILL THERE
		$route = preg_replace('/{\w+}\//', '', $route);
		//CLEAN ROUTE IF DOUBLE BAR FOUND
		$route = preg_replace('/\/\//', '/', $route);

		global $base_url, $host;

		if ($absolute_path) return $host.$base_url.$route;
		else return $base_url.$route;
	}

}
?>
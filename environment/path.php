<?php
class Path {

	private $routes;
	private $paths;
	
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
						else if ($clean_subs[$i] != '') break;
					}
					if ($is_match) {
						for ($i = 0; $i<count($route_subs); $i++) {
							if (preg_match('/{\w+}/', $route_subs[$i])) {
								$param_name = str_replace(array('{','}'), '', $route_subs[$i]);
								$params[$param_name] = $path_subs[$i];
							}
						}
						$params = $this->params_from_key(array_search($route, $routes), $params);
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

	public function __call($name, $args) {
		$absolute_path = $name[0] == '_';
		if ($absolute_path) $name = substr($name, 1);
		if (isset($args) && isset($args[0])) {
			if (is_object($args[0]) || is_array($args[0])) $arr = (array)$args[0];
			else if (is_string($args[0])) $arr = $args[0];
		}
		
		if (isset($this->paths[$name])) {
			$route = $this->paths[$name]['route'];
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
						$route_has_query = true;
						$route.='?'.http_build_query($arr);
					}
				}
				//SI EN LA URL NO HAY NADA QUE REEMPLAZAR EL ARRAY SE CONVIERTE A QUERY
				else {
					$route_has_query = true;
					$route.='?'.http_build_query($arr);
				};
			}

			//SI EL PRIMER ARGUMENTO ES UN STRING SE USA COMO QUERY
			if (isset($arr) && is_string($arr)) {
				$route_has_query = true;
				$route.=$arr;
			};
			
			//CLEAN ROUTE IF PATTERN STILL THERE
			$route = preg_replace('/{\w+}\//', '', $route);
			//CLEAN ROUTE IF DOUBLE BAR FOUND
			$route = preg_replace('/\/\//', '/', $route);

			global $base_url, $host, $app;

			//SET LOCALE TO ROUTE IF LOCATE PARAM PRESENT OR IF SECOND ARGUMENT IS PRESENT TO FORCE A LOCALE
			$locale_param = $app->settings['locale']['param'];
			$route_locale = isset($args[1]) ? $args[1] : (isset($_GET[$locale_param]) ? $_GET[$locale_param] : false);
			if ($route_locale) {
				if (isset($route_has_query) && $route_has_query) $route.='&'.$locale_param.'='.$route_locale;
				else $route.='?'.$locale_param.'='.$route_locale;
			};

			if ($absolute_path) return $host.$base_url.$route;
			return $base_url.$route;
		}
		return '';
	}

}
?>
<?php
class in398n {
	public function __construct() {
		$parsed_ini = parse_ini_file('../config/app.ini', true);
		$this->config = $parsed_ini['locale'];
		$param = $this->config['param'];
		$default = $this->config['default'];

		$this->language = isset($_GET[$param]) && file_exists('../config/locales/'.$_GET[$param].'.ini') ? $_GET[$param] : $default;

		$this->l = parse_ini_file('../config/locales/'.$this->language.'.ini', true);

		function current_language() {
			global $in398n;
			return $in398n->language;
		}

		function _l($str) {
			global $in398n;
			$keys = explode('.', $str);
			foreach ($keys as $i => $k) {
				if (!$i) $r = isset($in398n->l[$k]) ? $in398n->l[$k] : false;
				else $r = isset($r[$k]) ? $r[$k] : false;
			}
			if (preg_match_all('/\{\{([\w\d\.]+)\}\}/', $r, $matches)) {
				foreach ($matches[1] as $str) {
					$r = str_replace('{{'.$str.'}}', _l($str), $r);
				}
			}
			return $r;
		};

		function l($str) {
			echo _l($str);
		};

		function change_locale($lng) {
			global $in398n;
			$current = preg_replace('/[\?&]?'.$in398n->config['param'].'=\w+/', '', $_SERVER['REQUEST_URI']);
			if ($lng == $in398n->config['default']) echo $current;
			elseif (strpos($current, '?') !== false) echo $current.'&'.$in398n->config['param'].'='.$lng;
			else echo $current.'?'.$in398n->config['param'].'='.$lng;
		}
	}
}
?>
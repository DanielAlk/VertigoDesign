<?php
class Locale {
	public function __construct() {
		$parsed_ini = parse_ini_file('../config/app.ini', true);
		$this->config = $parsed_ini['locale'];
		$param = $this->config['param'];
		$default = $this->config['default'];

		$this->language = isset($_GET[$param]) && file_exists('../config/locales/'.$_GET[$param].'.ini') ? $_GET[$param] : $default;

		$this->l = parse_ini_file('../config/locales/'.$this->language.'.ini', true);

		function _l($str) {
			global $locale;
			$keys = explode('.', $str);
			foreach ($keys as $i => $k) {
				if (!$i) $r = $locale->l[$k];
				else $r = $r[$k];
			}
			return $r;
		};

		function l($str) {
			echo _l($str);
		};
	}
}
?>
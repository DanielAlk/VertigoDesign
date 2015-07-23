<?php
class Locale {
	public function __construct() {
		$parsed_ini = parse_ini_file('../config/app.ini', true);
		$this->config = $parsed_ini['locale'];
		$param = $this->config['param'];
		$default = $this->config['default'];

		$this->language = isset($_GET[$param]) && file_exists('../config/locales/'.$_GET[$param].'.ini') ? $_GET[$param] : $default;

		$this->l = parse_ini_file('../config/locales/'.$this->language.'.ini', true);

		function l($a, $b, $c = false) {
			global $locale;
			if ($c !== false && isset($locale->l[$a][$b][$c])) print $locale->l[$a][$b][$c];
			else if (isset($locale->l[$a][$b])) print $locale->l[$a][$b];
		}
	}
}
?>
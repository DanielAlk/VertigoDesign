<?php
class Asset {
	public function __construct() {
		global $base_url;
		foreach ($this->assets as $key => $asset) {
			$this->assets[$key][0] = get_include_path().$asset[0];
			$this->assets[$key][1] = $base_url.$asset[1];
		}
	}
	
	public function path($str) {
		$arr = explode('.', $str);
		$ext = end($arr);
		print $this->assets[$ext][1].$str;
	}
	
	public function get_all_from_dir($dir, $type = 'jpg', $filter = '') {
		$rtn = array();
		$path = $this->assets[$type][0].$dir.'/'.$filter.'*.'.$type;
		foreach(glob($path) as $filename) {
			$rtn[] = $this->assets[$type][1].str_replace($this->assets[$type][0], '', $filename);
		}
		return $rtn;
	}
	
	public function __call($ext, $args = array()) {
		$filename = isset($args[0]) ? $args[0] : 'main';
		print $this->assets[$ext][1].$filename.".$ext";
	}

	private $assets = array(
		'js' => array('assets/javascripts/', 'js/'),
		'css' => array('assets/stylesheets/', 'css/'),
		'jpg' => array('assets/img/', 'img/'),
		'png' => array('assets/img/', 'img/'),
		'gif' => array('assets/img/', 'img/'),
		'ico' => array('assets/img/', 'img/'),
		'eot' => array('assets/fonts/', 'fonts/'),
		'woff' => array('assets/fonts/', 'fonts/'),
		'ttf' => array('assets/fonts/', 'fonts/'),
		'svg' => array('assets/fonts/', 'fonts/')
		);
}
?>
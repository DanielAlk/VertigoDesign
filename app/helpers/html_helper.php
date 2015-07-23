<?php
class html_helper {

	public function nav_class($call) {
		global $path;
		print $path->$call() == $_SERVER["REQUEST_URI"] ? 'active' : '';
	}

	public function render($echos = array(), $file) {
		foreach($GLOBALS as $key => $value) {
			$$key = $value;
		}
		foreach ($echos as $key => $value) {
			$$key = $value;
		}
		include $file;
	}

}
?>
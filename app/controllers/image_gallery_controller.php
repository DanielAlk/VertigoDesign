<?php
class image_gallery_controller extends ApplicationController {
	protected $before_actions = array(
		array('set_layout')
		);

	protected function index() {
		global $params;
		$GLOBALS['gallery'] = $this->get_gallery($params['dir'], $params['filter']);
	}

	protected function set_layout() {
		$this->layout = 'layouts/clean_view.php';
	}

	private function get_gallery($dir, $filter, $type = 'jpg') {
		global $asset;
		$dir = '_galleries'.DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR;
		$rtn = array();
		$rtn_full = $asset->get_folder($dir.'full', $type, $filter);
		$rtn_thumb = $asset->get_folder($dir.'thumbnails', $type, $filter);
		for ($i = 0; $i<count($rtn_full); $i++) {
			$rtn[basename($rtn_full[$i])] = array("full" => $rtn_full[$i], "thumb" => $rtn_thumb[$i]);
		}
		die();
		return $rtn;
	}
}
?>
<?php
class image_gallery_controller extends ApplicationController {
	protected $before_actions = array(
		array('set_layout'),
		array('set_cache'),
		array('validate_params')
		);

	protected function index() {
		global $params;
		$GLOBALS['gallery'] = $this->get_gallery($params['dir']);
	}

	protected function search() {
		global $params;
		$GLOBALS['gallery'] = $this->get_gallery($params['dir'], $params['filter']);
		$this->render('index');
	}

	private function get_gallery($dir, $filter = '*', $type = 'jpg') {
		global $asset;
		$dir = '_galleries'.DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR;
		$rtn = array();
		$rtn_full = $asset->get_folder($dir.'full', $type, $filter, true);
		$rtn_thumb = $asset->get_folder($dir.'thumbnails', $type, $filter);
		for ($i = 0; $i<count($rtn_full); $i++) {
			$filename = $rtn_full[$i]['file'];
			$image_data = $rtn_full[$i]['data'];
			$rtn[basename($filename)] = array('full' => $filename, 'data' => $image_data, 'thumb' => $rtn_thumb[$i]);
		}
		return $rtn;
	}

	protected function validate_params() {
		global $params;
		$permited = array('branding', 'mobile', 'packaging', 'print', 'web');
		if (!in_array($params['dir'], $permited)) {
			$this->not_found();
		}
	}

	protected function set_layout() {
		$this->layout = 'layouts/clean_view.php';
	}

	protected function set_cache() {
		$this->cache_control('revalidate');
	}
}
?>
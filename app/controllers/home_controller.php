<?php
class home_controller extends ApplicationController {
	protected $before_actions = array(
		array(array('index'), 'detect_mobile')
		);

	protected function index($data) {
		$media = $data->detect_mobile; global $asset;

		$highlight_images_dir = 'highlight';
		if ($media == 'mobile') $highlight_images_dir.='-mobile';
		$GLOBALS['highlight_images'] = $asset->get_all_from_dir($highlight_images_dir);

		$GLOBALS['animation_images'] = $asset->get_all_from_dir('animation');

		$GLOBALS['branding_images'] = $this->images_data('branding', 'png');

		$GLOBALS['mobile_images'] = $this->images_data('mobile');

		$GLOBALS['web_0_images'] =  $this->images_data('web-0', 'png');
		$GLOBALS['web_1_images'] =  $this->images_data('web-1', 'png');
		$GLOBALS['web_2_images'] =  $this->images_data('web-2', 'png');
		$GLOBALS['web_3_images'] =  $this->images_data('web-3', 'png');

		$GLOBALS['print_images'] = $this->images_data('print', 'png');

		$GLOBALS['packaging_images'] = $this->images_data('packaging', 'png');

		$GLOBALS['media'] = $media;
	}

	protected function images_data($dir, $type = 'jpg') {
		global $asset;
		$rtn = array();
		$images = $asset->get_all_from_dir($dir, $type);
		if (array_key_exists($dir, $this->gallery_data)) {
			$data = $this->gallery_data[$dir];
			for ($i = 0; $i<count($images); $i++) {
				$rtn[] = array('image'=>$images[$i], 'data'=>$data[$i]);
			}
		} else foreach ($images as $img) {
			$name = str_replace('.'.$type, '', end(explode('/', $img)));
			$rtn[] = array('image'=>$img, 'data'=>$name);
		}
		return $rtn;
	}

	protected $gallery_data = array(
		'branding' => array(
			array('mordisco','annunziatta','mustmobile'),
			array('adagio','brokenheart','palermo'),
			array('grupo_zaval','musikrte','joker'),
			array('silenzia','free_pc_secure','appartango'),
			array('everypost','aurametal','el_camino_del_vino'),
			array('dalo_por_hecho','msmegashows','southbits'),
			array('miami_ya','stylez')
			),
		'packaging' => array(
			array('amarula','silenzia','criaturas'),
			array('david-soler','el_camino_del_vino','elvira-vasil'),
			array('error-inspector','error-protector','skins')
			)
		);

}
?>
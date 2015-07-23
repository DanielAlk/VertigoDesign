<?php
class ApplicationController extends App {

	protected function detect_mobile() {
		global $Mobile_Detect;
		if ($Mobile_Detect->isTablet()) return 'tablet';
		else if ($Mobile_Detect->isMobile()) return 'mobile';
		else return 'desktop';
	}

}
?>
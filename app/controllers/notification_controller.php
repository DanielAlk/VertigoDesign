<?php
class notification_controller extends ApplicationController {
	protected $before_actions = array(
		array('filter_post_requests'),
		array('choose_layout')
		);

	protected function user_info() {
		global $mailer;
		$GLOBALS['name'] = 'Juan';

		$mailer->mail(array('daniognr@hotmail.com', 'Dani Alk'), 'hola!');
	}

	protected function new_sale() {
		global $mailer;
		$GLOBALS['name'] = 'Juan';

		$mailer->notify('NEW SALE', function($status) {
			global $path;
			if ($status) redirect_to($path->home());
			else return $status;
		});
	}

	protected function filter_post_requests() {
		# NO CACHE FOR THIS PAGE
		$GLOBALS['page_headers'] = array();
		# TODO: USE $_SERVER['REQUEST_METHOD'] FOR GETTING IF ITS POST OR GET REQUEST
	}

	protected function choose_layout() {
		$this->layout = 'layouts/mails.php';
	}

}
?>
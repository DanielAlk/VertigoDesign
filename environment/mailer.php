<?php
class Mailer {
	private $settings;
	protected $mailer;

	public function __construct($settings = null) {
		if (!$settings) return;
		require '../extensions/mailer/class.phpmailer.php';
		require '../extensions/mailer/class.smtp.php';
		$this->settings = $settings;
		$this->mailer = new PHPMailer();

		$this->mailer->isSMTP(); // Set mailer to use SMTP
		$this->mailer->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
				)
			);
		$this->mailer->Host = $settings['host']; // Specify main and backup SMTP servers
		$this->mailer->SMTPAuth = true; // Enable SMTP authentication
		$this->mailer->Username = $settings['username']; // SMTP username
		$this->mailer->Password = $settings['password']; // SMTP password
		$this->mailer->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
		$this->mailer->Port = 25;
		$this->mailer->CharSet = 'UTF-8';
		$this->mailer->SetFrom($settings['from_email'], $settings['from_name']);
	}

	public function mail($to = false, $subject, $callback = false) {
		if ($to === false) return;
		$callback = $callback !== false ? $callback : function($status) { return json_encode($status); };
		$this->mailer->AddAddress($to[0], $to[1]);
		$this->mailer->Subject = isset($subject) ? $subject : 'Default Subject';
		$mailer = $this->mailer;
		ob_start(function($buffer) use ($callback, $mailer) {
			$mailer->MsgHTML($buffer);
			return $callback($mailer->Send(), $buffer);
		});
	}

	public function notify($subject, $callback = false) {
		$settings = $this->settings;
		$to = array($settings['notify_email'], $settings['notify_name']);
		$this->mail($to, $subject, $callback);
	}
}
?>
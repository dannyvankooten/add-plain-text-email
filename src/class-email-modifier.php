<?php

use Html2Text\Html2Text;

class APTE_Email_Modifier {

	/**
	 * @var string
	 */
	protected $previous_altbody;

	/**
	 * Hooks into PHPMailer to set the AltBody property with a plain/text email
	 */
	public function add_hooks() {
		add_action('phpmailer_init', array( $this, 'set_altbody' ) );
	}

	/**
	 * @param PHPMailer $phpmailer
	 */
	public function set_altbody( $phpmailer ) {

		// don't run if sending plain text email already
		if( $phpmailer->ContentType === 'text/plain' ) {
			return;
		}

		// don't run if altbody is set (by other plugin)
		if( ! empty( $phpmailer->AltBody ) && $phpmailer->AltBody !== $this->previous_altbody ) {
			return;
		}

		// create text version of message
		$text_message = Html2Text::convert( $phpmailer->Body );

		// set AltBody
		$phpmailer->AltBody = $text_message;
		$this->previous_altbody = $text_message;
	}

}
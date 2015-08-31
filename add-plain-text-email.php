<?php
/*
Plugin Name: Add Plain Text Email
Plugin URI: http://dannyvankooten.com/wordpress-plugins/mailchimp-for-wordpress/
Description: Adds a text/plain email to text/html emails to decrease the chance of emails being tagged as spam.
Version: 1.1.1
Author: Danny van Kooten
Author URI: http://dannyvanKooten.com
*/

defined( 'ABSPATH' ) OR exit;

class APTE {

	/**
	 * @var string
	 */
	protected $previous_altbody;

	/**
	 * Add hooks
	 */
	public function add_hooks() {
		// add action so function actually runs
		add_action('phpmailer_init', array( $this, 'set_plaintext_body' ) );
	}

	/**
	 * @param PHPMailer $phpmailer
	 */
	public function set_plaintext_body( $phpmailer ) {

		// don't run if sending plain text email already
		if( $phpmailer->ContentType === 'text/plain' ) {
			return;
		}

		// don't run if altbody is set (by other plugin)
		if( ! empty( $phpmailer->AltBody ) && $phpmailer->AltBody !== $this->previous_altbody ) {
			return;
		}

		// set AltBody
		$text_message = $this->strip_html_tags( $phpmailer->Body );
		var_dump( $text_message ); die();
		$phpmailer->AltBody = $text_message;
		$this->previous_altbody = $text_message;
	}

	/**
	 * Remove HTML tags, including invisible text such as style and
	 * script code, and embedded objects.  Add line breaks around
	 * block-level tags to prevent word joining after tag removal.
	 */
	private function strip_html_tags( $text ) {
	    $text = preg_replace(
	        array(
	          // Remove invisible content
	            '@<head[^>]*?>.*?</head>@siu',
	            '@<style[^>]*?>.*?</style>@siu',
	            '@<script[^>]*?.*?</script>@siu',
	            '@<object[^>]*?.*?</object>@siu',
	            '@<embed[^>]*?.*?</embed>@siu',
	            '@<noscript[^>]*?.*?</noscript>@siu',
	            '@<noembed[^>]*?.*?</noembed>@siu',
		        '@\t+@siu',
		        '@\n+@siu'
	        ),
	        '',
	        $text );

		// replace certain elements with a line-break
		$text = preg_replace(
			array(
				'@</?((div)|(h[1-9])|(/tr)|(p)|(pre))@iu'
			),
			"\n\$0",
			$text );

		// replace other elements with a space
		$text = preg_replace(
			array(
				'@</((td)|(th))@iu'
			),
			" \$0",
			$text );

		// strip all remaining HTML tags
	    $text = strip_tags( $text );

		// trim text
		$text = trim( $text );

		return $text;
	}

}

$apte = new APTE;
$apte->add_hooks();

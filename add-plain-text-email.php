<?php
/*
Plugin Name: Add Plain Text Email
Plugin URI: http://dannyvankooten.com/wordpress-plugins/mailchimp-for-wordpress/
Description: Adds a text/plain email to text/html emails to decrease the chance of emails being tagged as spam.
Version: 1.1
Author: Danny van Kooten
Author URI: http://dannyvankooten.com
*/

defined( 'ABSPATH' ) OR exit;

// only run on PHP 5.3+
if( version_compare( PHP_VERSION, '5.3', '>=' ) ) {

	// include autoloader (only once)
	require_once __DIR__ . '/vendor/autoload.php';

	// create instance of class
	$modifier = new APTE_Email_Modifier();
	$modifier->add_hooks();
}



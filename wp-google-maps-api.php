<?php
/*
Plugin Name: WordPress Google Maps API
Plugin URI: https://github.com/miiitaka/wp-google-maps-api
Description:
Version: 1.0.0
Author: Kazuya Takami
Author URI: http://programp.com/
License: GPLv2 or later
Text Domain: wp-google-maps-api
Domain Path: /languages
*/
new WP_Google_Maps_Api();

/**
 * Basic Class
 *
 * @author  Kazuya Takami
 * @version 1.0.0
 * @since   1.0.0
 */
class WP_Google_Maps_Api {

	/**
	 * Variable definition.
	 *
	 * @since 1.0.0
	 */
	private $text_domain = 'wp-google-maps-api';

	/**
	 * Constructor Define.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	public function __construct () {

	}
}
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
require_once( plugin_dir_path( __FILE__ ) . 'includes/wp-google-maps-api-admin-db.php' );

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
		if ( is_admin() ) {
			add_action( 'admin_init', array( $this, 'admin_init' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		}
	}

	/**
	 * admin init.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	public function admin_init () {
		wp_register_style( 'wp-google-maps-api-admin-style', plugins_url( 'css/map.css', __FILE__ ), array(), '1.0.0' );
	}

	/**
	 * Add Menu to the Admin Screen.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	public function admin_menu () {
		add_menu_page(
			esc_html__( 'Google Maps Settings', $this->text_domain ),
			esc_html__( 'Google Maps Settings', $this->text_domain ),
			'manage_options',
			plugin_basename( __FILE__ ),
			array( $this, 'list_page_render' )
		);
		$list_page = add_submenu_page(
			__FILE__,
			esc_html__( 'All Settings', $this->text_domain ),
			esc_html__( 'All Settings', $this->text_domain ),
			'manage_options',
			plugin_basename( __FILE__ ),
			array( $this, 'list_page_render' )
		);
		$post_page = add_submenu_page(
			__FILE__,
			esc_html__( 'Google Maps API', $this->text_domain ),
			esc_html__( 'Add New',         $this->text_domain ),
			'manage_options',
			$this->text_domain . '-post',
			array( $this, 'post_page_render' )
		);

		add_action( 'admin_print_styles-'  . $post_page, array( $this, 'add_style' ) );
		add_action( 'admin_print_styles-'  . $list_page, array( $this, 'add_style' ) );
		add_action( 'admin_print_scripts-' . $post_page, array( $this, 'admin_scripts') );
	}

	/**
	 * Admin List Page Template Require.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	public function list_page_render () {
		require_once( plugin_dir_path( __FILE__ ) . 'includes/wp-google-maps-api-admin-list.php' );
		new WP_Google_Maps_Api_Admin_List( $this->text_domain );
	}

	/**
	 * Admin Post Page Template Require.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	public function post_page_render () {
		require_once( plugin_dir_path( __FILE__ ) . 'includes/wp-google-maps-api-admin-post.php' );
		new WP_Google_Maps_Api_Admin_Post( $this->text_domain );
	}

	/**
	 * CSS admin add.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	public function add_style () {
		wp_enqueue_style( 'wp-google-maps-api-admin-style' );
	}

	/**
	 * admin_scripts
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	public function admin_scripts () {
		wp_enqueue_script ( 'wp-google-maps-api-map-js', plugins_url ( 'js/map.js', __FILE__ ), array( 'jquery' ), '1.0.0' );
	}
}
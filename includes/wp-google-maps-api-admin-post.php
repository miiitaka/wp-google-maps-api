<?php
/**
 * Google Maps API Admin Setting
 *
 * @author  Kazuya Takami
 * @since   1.0.0
 * @version 1.0.0
 * @see     https://developers.google.com/maps/web/
 */
class WP_Google_Maps_Api_Admin_Post {

	/**
	 * Variable definition.
	 *
	 * @since 1.0.0
	 */
	private $text_domain;

	/**
	 * Constructor Define.
	 *
	 * @since 1.0.0
	 * @param String $text_domain
	 */
	public function __construct ( $text_domain ) {
		$this->text_domain = $text_domain;

		/**
		 * Update Status
		 *
		 * "ok" : Successful update
		 */
		$status = "";

		/** DB Connect */
		$db = new WP_Google_Maps_Api_Admin_Db();

		/** Set Default Parameter for Array */
		$options = array(
			"id"                => "",
			"type"              => "",
			"template_name"     => "",
			"template"          => '<img src="##image##">' . PHP_EOL . '<span>##date##</span>' . PHP_EOL . '<span><a href="##link##">##title##</a></span>',
			"template_no_image" => "",
			"save_term"         => 7,
			"save_item"         => 10,
			"output_data"       => ""
		);

		/** Key Set */
		if ( isset( $_GET['google_maps_api_id'] ) && is_numeric( $_GET['google_maps_api_id'] ) ) {
			$options['id'] = esc_html( $_GET['google_maps_api_id'] );
		}

		/** DataBase Update & Insert Mode */
		if ( isset( $_POST['id'] ) && is_numeric( $_POST['id'] ) ) {
			$db->update_options( $_POST );
			$options['id'] = $_POST['id'];
			$status = "ok";
		} else {
			if ( isset( $_POST['id'] ) && $_POST['id'] === '' ) {
				$options['id'] = $db->insert_options( $_POST );
				$status = "ok";
			}
		}

		/** Mode Judgment */
		if ( isset( $options['id'] ) && is_numeric( $options['id'] ) ) {
			$options = $db->get_options( $options['id'] );
		}

		$this->page_render( $options, $status );
	}

	/**
	 * Setting Page of the Admin Screen.
	 *
	 * @since   1.0.0
	 * @version 1.1.0
	 * @param   array  $options
	 * @param   string $status
	 */
	private function page_render ( array $options, $status ) {
		$html  = '';
		$html .= '<div class="wrap">';
		$html .= '<h1>' . esc_html__( 'Google Maps API Settings', $this->text_domain ) . '</h1>';
		echo $html;

		switch ( $status ) {
			case "ok":
				$this->information_render();
				break;
			default:
				break;
		}

		$html  = '<hr>';
		$html .= '<div>';
		$html .= '<form method="post" action="">';
		$html .= '<input type="hidden" name="id" value="' . esc_attr( $options['id'] ) . '">';
		echo $html;

		/** Common settings */
		$html  = '<table class="wp-posted-display-admin-table">';
		$html .= '<caption>' . esc_html__( 'Common settings', $this->text_domain ) . '</caption>';
		$html .= '<tr><th><label for="template_name">' . esc_html__( 'Template Name', $this->text_domain ) . ':</label></th><td>';
		$html .= '<input type="text" name="template_name" id="template_name" class="regular-text" required autofocus value="';
		$html .= esc_attr( $options['template_name'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="template">' . esc_html__( 'Template', $this->text_domain ) . ':</label></th><td>';
		$html .= '<textarea name="template" id="template" rows="10" cols="50" class="large-text code">' . $template = str_replace( '\\', '', $options['template'] ) . '</textarea>';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="template_no_image">' . esc_html__( 'No Image Path', $this->text_domain ) . ':</label></th><td>';
		$html .= '<input type="text" name="template_no_image" id="template_no_image" class="regular-text" value="';
		$html .= esc_attr( $options['template_no_image'] ) . '">';
		$html .= '<p>' . esc_html__( 'It specifies the posts of Alternative Image path that does not set the featured image.', $this->text_domain ) . '</p>';
		$html .= '</td></tr>';
		$html .= '</table>';
		echo $html;

		submit_button();

		$html  = '</form></div>';
		$html .= '<div><div id="map"></div></div>';
		$html .= '</div>';
		echo $html;
	}

	/**
	 * Information Message Render
	 *
	 * @since 1.0.0
	 */
	private function information_render () {
		$html  = '<div id="message" class="updated notice notice-success is-dismissible below-h2">';
		$html .= '<p>Posted Display Information Update.</p>';
		$html .= '<button type="button" class="notice-dismiss">';
		$html .= '<span class="screen-reader-text">Dismiss this notice.</span>';
		$html .= '</button>';
		$html .= '</div>';

		echo $html;
	}
}
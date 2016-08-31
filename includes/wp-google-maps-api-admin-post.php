<?php
/**
 * Google Maps API Admin Setting
 *
 * @author  Kazuya Takami
 * @version 1.0.0
 * @since   1.0.0
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
	 * @version 1.0.0
	 * @since   1.0.0
	 * @param   String $text_domain
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
			"id"      => "",
			"api_key" => "",
			"lat"     => 0,
			"lng"     => 0,
			"zoom"    => 8
		);

		/** Key Set */
		if ( isset( $_GET['google_maps_api_id'] ) && is_numeric( $_GET['google_maps_api_id'] ) ) {
			$options['id'] = esc_html( $_GET['google_maps_api_id'] );
		}

		/** DataBase Update & Insert Mode */
		if ( isset( $_POST['google_maps_api_id'] ) && is_numeric( $_POST['google_maps_api_id'] ) ) {
			$db->update_options( $_POST );
			$options['id'] = $_POST['google_maps_api_id'];
			$status = "ok";
		} else {
			if ( isset( $_POST['google_maps_api_id'] ) && $_POST['google_maps_api_id'] === '' ) {
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
	 * @version 1.0.0
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
		$html .= '<div class="map-api-wrapper">';
		echo $html;

		/* Form Content Area */
		$html  = '<div class="map-api-form">';
		$html .= '<form method="post" action="">';
		$html .= '<input type="hidden" name="google_maps_api_id" value="' . esc_attr( $options['id'] ) . '">';
		$html .= '<table>';
		$html .= '<tr><th><label for="map_name">' . esc_html__( 'Map Name', $this->text_domain ) . ':</label></th><td>';
		$html .= '<input type="text" name="map_name" id="map_name" class="regular-text" required autofocus value="';
		$html .= esc_attr( $options['api_key'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="api_key">' . esc_html__( 'YOUR API KEY', $this->text_domain ) . ':</label></th><td>';
		$html .= '<input type="text" name="api_key" id="api_key" class="regular-text" required value="';
		$html .= esc_attr( $options['api_key'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="lat">' . esc_html__( 'Latitude', $this->text_domain ) . ':</label></th><td>';
		$html .= '<input type="number" name="lat" id="lat" class="regular-text" required value="';
		$html .= esc_attr( $options['lat'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="lng">' . esc_html__( 'Longitude', $this->text_domain ) . ':</label></th><td>';
		$html .= '<input type="number" name="lng" id="lng" class="regular-text" required value="';
		$html .= esc_attr( $options['lng'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="zoom">' . esc_html__( 'Zoom', $this->text_domain ) . ':</label></th><td>';
		$html .= '<input type="number" name="zoom" id="zoom" class="small-text" required value="';
		$html .= esc_attr( $options['zoom'] ) . '">';
		$html .= '</td></tr>';
		$html .= '</table>';
		echo $html;

		submit_button();

		$html  = '</form>';
		$html .= '<input type="button" value="Preview Map" class="button" id="preview-map">';
		$html .= '</div>';

		/* Map Preview Area */
		$html .= '<div class="map-api-preview">';
		$html .= '<div id="wp-google-map-api-map"></div>';
		$html .= '</div>';

		$html .= '</div>';
		$html .= '<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC1AyT4iW49GxlDd4DsZ21TyscGzQLNpnE&callback=initMap" async defer></script>';
		echo $html;
	}

	/**
	 * Information Message Render
	 *
	 * @since 1.0.0
	 */
	private function information_render () {
		$html  = '<div id="message" class="updated notice notice-success is-dismissible below-h2">';
		$html .= '<p>Google Map API Information Update.</p>';
		$html .= '<button type="button" class="notice-dismiss">';
		$html .= '<span class="screen-reader-text">Dismiss this notice.</span>';
		$html .= '</button>';
		$html .= '</div>';

		echo $html;
	}
}
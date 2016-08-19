<?php
/**
 * Admin Form Build
 *
 * @author  Kazuya Takami
 * @version 1.0.0
 * @since   1.0.0
 */

class WP_Google_Maps_Api_Form_Build {

	/**
	 * Table Form Number.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @access  private
	 * @param   string  $id
	 * @param   string  $name
	 * @param   boolean $value
	 * @param   string  $text
	 */
	public function number ( $id, $name, $value, $text ) {
		printf( '<p><label for="%s">%s</label>', $id, $text );
		printf( '<input type="number" id="%s" name="%s" value="%s" class="small-text">', $id, $name, esc_attr( $value ) );
		echo '</p>';
	}

}
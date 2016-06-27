<?php
/**
 * Admin DB Connection
 *
 * @author  Kazuya Takami
 * @version 1.0.0
 * @since   1.0.0
 */
class WP_Google_Maps_Api_Admin_Db {

	/**
	 * Variable definition.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	private $table_name;

	/**
	 * Constructor Define.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	public function __construct () {
		global $wpdb;
		$this->table_name = $wpdb->prefix . 'google_maps_api';
	}

	/**
	 * Create Table.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	public function create_table () {
		global $wpdb;

		$prepared     = $wpdb->prepare( "SHOW TABLES LIKE %s", $this->table_name );
		$is_db_exists = $wpdb->get_var( $prepared );

		if ( is_null( $is_db_exists ) ) {
			$charset_collate = $wpdb->get_charset_collate();

			$query  = " CREATE TABLE " . $this->table_name;
			$query .= " (id mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY";
			$query .= ",type tinytext NOT NULL";
			$query .= ",template_name tinytext NOT NULL";
			$query .= ",template text NOT NULL";
			$query .= ",template_no_image text";
			$query .= ",save_term int DEFAULT 7";
			$query .= ",save_item int DEFAULT 10";
			$query .= ",output_data tinytext";
			$query .= ",register_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL";
			$query .= ",update_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL";
			$query .= ",UNIQUE KEY id (id)) " . $charset_collate;

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $query );
		}
	}

	/**
	 * Get Data.
	 *
	 * @since  1.0.0
	 * @param  integer $id
	 * @return array   $args
	 */
	public function get_options ( $id ) {
		global $wpdb;

		$query    = "SELECT * FROM " . $this->table_name . " WHERE id = %d";
		$data     = array( $id );
		$prepared = $wpdb->prepare( $query, $data );

		return (array) $wpdb->get_row( $prepared );
	}

	/**
	 * Get All Data.
	 *
	 * @since  1.0.0
	 * @param  string $type
	 * @return array  $results
	 */
	public function get_list_options ( $type = null ) {
		global $wpdb;

		if ( $type === 'Cookie' ) {
			$query    = "SELECT * FROM " . $this->table_name . " WHERE type = %s ORDER BY update_date DESC";
			$data     = array( $type );
			$prepared = $wpdb->prepare( $query, $data );
		} else {
			$prepared = "SELECT * FROM " . $this->table_name . " ORDER BY update_date DESC";
		}
		return (array) $wpdb->get_results( $prepared );
	}

	/**
	 * Insert Data.
	 *
	 * @since  1.0.0
	 * @param  array $post($_POST)
	 * @return integer $id
	 */
	public function insert_options ( array $post ) {
		global $wpdb;

		$output_data = $this->set_output_data( $post );

		$data = array(
			'template_name'     => strip_tags( $post['template_name'] ),
			'type'              => strip_tags( $post['type'] ),
			'template'          => preg_replace('!<script.*?>.*?</script.*?>!is', '', $post['template'] ),
			'template_no_image' => strip_tags( $post['template_no_image'] ),
			'save_term'         => $post['save_term'],
			'save_item'         => $post['save_item'],
			'output_data'       => strip_tags( $output_data ),
			'register_date'     => date( "Y-m-d H:i:s" ),
			'update_date'       => date( "Y-m-d H:i:s" )
		);
		$prepared = array(
			'%s',
			'%s',
			'%s',
			'%s',
			'%d',
			'%d',
			'%s',
			'%s',
			'%s'
		);

		$wpdb->insert( $this->table_name, $data, $prepared );
		return (int) $wpdb->insert_id;
	}

	/**
	 * Update Data.
	 *
	 * @since 1.0.0
	 * @param array $post($_POST)
	 */
	public function update_options ( array $post ) {
		global $wpdb;

		$output_data = $this->set_output_data( $post );

		$data = array(
			'template_name'     => strip_tags( $post['template_name'] ),
			'type'              => strip_tags( $post['type'] ),
			'template'          => preg_replace('!<script.*?>.*?</script.*?>!is', '', $post['template'] ),
			'template_no_image' => strip_tags( $post['template_no_image'] ),
			'save_term'         => $post['save_term'],
			'save_item'         => $post['save_item'],
			'output_data'       => strip_tags( $output_data ),
			'update_date'       => date( "Y-m-d H:i:s" )
		);
		$key = array( 'id' => esc_html( $post['id'] ) );
		$prepared = array(
			'%s',
			'%s',
			'%s',
			'%s',
			'%d',
			'%d',
			'%s',
			'%s'
		);
		$key_prepared = array( '%d' );

		$wpdb->update( $this->table_name, $data, $key, $prepared, $key_prepared );
	}

	/**
	 * Delete Data.
	 *
	 * @since 1.0.0
	 * @param integer $id
	 */
	public function delete_options ( $id ) {
		global $wpdb;

		$key = array( 'id' => esc_html( $id ) );
		$key_prepared = array( '%d' );

		$wpdb->delete( $this->table_name, $key, $key_prepared );

		/** Widget delete. */
		$options = get_option( 'widget_posted_display_widget' );

		if ( $options ) {
			foreach ( $options as $key => $option ) {
				if ( isset( $option['template'] ) && $option['template'] == $id ) {
					unset( $options[$key] );
				}
			}
			update_option( 'widget_posted_display_widget', $options );
		}
	}

	/**
	 * Query Settings.
	 *
	 * @since   1.0.0
	 * @version 1.0.4
	 * @access  public
	 * @param   array  $results
	 * @param   array  $instance
	 * @param   string $cookie_name
	 * @return  array  $args
	 */
	public function set_query ( $results, $instance, $cookie_name ) {
		/** Common Items Set */
		$args = array(
			"post_status"         => "publish",
			"posts_per_page"      => esc_html( $instance['posts'] ),
			"ignore_sticky_posts" => 1
		);

		switch ( $instance['sort'] ) {
			case 0: $args += array( "orderby" => "post__in", "order" => "ASC" ); break;
			case 1: $args += array( "orderby" => "date", "order" => "DESC" ); break;
			case 2: $args += array( "orderby" => "date", "order" => "ASC" ); break;
			case 3: $args += array( "orderby" => "rand" ); break;
		}

		switch ( $results['type'] ) {
			case "Cookie":
				if ( isset( $_COOKIE[$cookie_name] ) ) {
					$args += array( "post__in" => array_reverse( explode( ',', esc_html( $_COOKIE[$cookie_name] ) ) ) );
				} else {
					$args = array();
				}
				break;
			case "Posts":
				$args += array( "post__in" => explode( ',', esc_html( $results['output_data']) ) );
				break;
			case "Categories":
				$args += array( "category__in" => explode( ',', esc_html( $results['output_data']) ) );
				break;
			case "Tags":
				$args += array( "tag__in" => explode( ',', esc_html( $results['output_data']) ) );
				break;
			case "Users":
				$args += array( "author__in" => explode( ',', esc_html( $results['output_data']) ) );
				break;
		}

		return (array) $args;
	}

	/**
	 * Query Settings.
	 *
	 * @since   1.0.0
	 * @version 1.1.0
	 * @access  private
	 * @param   array  $post
	 * @return  string $return_output_data
	 */
	private function set_output_data ( $post ) {
		$return_output_data = "";
		switch ( $post['type'] ) {
			case "Cookie":
				break;
			case "Posts":
				$return_output_data = isset( $post['posts_output_data'] ) ? $post['posts_output_data'] : "";
				break;
			case "Categories":
				$return_output_data = isset( $post['categories_output_data'] ) ? $post['categories_output_data'] : "";
				break;
			case "Tags":
				$return_output_data = isset( $post['tags_output_data'] ) ? $post['tags_output_data'] : "";
				break;
			case "Users":
				$return_output_data = isset( $post['users_output_data'] ) ? $post['users_output_data'] : "";
				break;
		}
		return (string) $return_output_data;
	}

	/**
	 * Template replace.
	 *
	 * @since   1.0.0
	 * @version 1.1.1
	 * @access  public
	 * @param   string $template
	 * @param   array  $items
	 * @return  string $template
	 * 
	 * @note Array Format
	 * [
	 *   "title"      : string,
	 *   "excerpt"    : string,
	 *   "image"      : string,
	 *   "date"       : string,
	 *   "link"       : string,
	 *   "tag"        : string,
	 *   "category"   : string,
	 *   "author_name": string
	 * ]
	 */
	public function set_template ( $template, $items ) {
		$template = str_replace( '##title##',       esc_html( $items["title"] ),       $template );
		$template = str_replace( '##summary##',     esc_html( $items["excerpt"] ),     $template );
		$template = str_replace( '##image##',       esc_html( $items["image"] ),       $template );
		$template = str_replace( '##date##',        esc_html( $items["date"] ),        $template );
		$template = str_replace( '##link##',        esc_url( $items["link"]),          $template );
		$template = str_replace( '##tag##',         esc_html( $items["tag"] ),         $template );
		$template = str_replace( '##category##',    esc_html( $items["category"] ),    $template );
		$template = str_replace( '##author_name##', esc_html( $items["author_name"] ), $template );
		$template = str_replace( '\\', '', $template );

		/** Escape */
		$template = preg_replace('!<script.*?>.*?</script.*?>!is', '', $template );
		$template = preg_replace('!onerror=".*?"!is', '', $template );
		return (string) $template;
	}
}
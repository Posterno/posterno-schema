<?php
/**
 * The class that holds all storage methods for the schema.
 *
 * @package     posterno_schema
 * @copyright   Copyright (c) 2018, Pressmodo, LLC
 * @license     http://opensource.org/licenses/gpl_2.0.php GNU Public License
 */

namespace PNO\SchemaOrg\Settings;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Handles sanitization of schema properties values.
 */
class SettingsStorage {

	/**
	 * Save schema to the database.
	 *
	 * @param string $schema_id the id of the post in the database.
	 * @param string $mode the display mode of the schema.
	 * @param string $title the title assigned to the schema.
	 * @param array  $listing_types optional listing types selected if the mode allows for it.
	 * @param array  $properties list of schema properties mapped to fields.
	 * @return string
	 */
	public static function save( $schema_id, $mode, $title, $listing_types, $properties ) {

		$post_id = false;

		$schema_to_update = array(
			'ID'         => $schema_id,
			'post_title' => $title,
			'post_type'  => 'pno_schema',
		);

		$post_id = wp_update_post( $schema_to_update );

		if ( is_wp_error( $post_id ) ) {
			return $post_id;
		}

		update_post_meta( $post_id, 'schema_mode', $mode );

		if ( $mode === 'type' ) {
			update_post_meta( $post_id, 'schema_listing_types', $listing_types );
		} else {
			delete_post_meta( $post_id, 'schema_listing_types' );
		}

		if ( ! empty( $properties ) && is_array( $properties ) ) {
			update_post_meta( $post_id, 'schema_code', wp_json_encode( $properties ) );
		}

		return $post_id;

	}

}

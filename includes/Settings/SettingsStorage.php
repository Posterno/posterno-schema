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
	 * @param string $name the main schema name.
	 * @param string $mode the display mode of the schema.
	 * @param string $title the title assigned to the schema.
	 * @param array  $listing_types optional listing types selected if the mode allows for it.
	 * @param string $primary_schema optional schema type.
	 * @param string $secondary_schema optional schema type.
	 * @param string $tertiary_schema optional schema type.
	 * @param array  $properties list of schema properties mapped to fields.
	 * @return string
	 */
	public static function save( $schema_id, $name, $mode, $title, $listing_types, $primary_schema, $secondary_schema, $tertiary_schema, $properties ) {

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
		update_post_meta( $post_id, 'schema_name', $name );

		if ( $mode === 'type' ) {
			update_post_meta( $post_id, 'schema_listing_types', $listing_types );
		} else {
			delete_post_meta( $post_id, 'schema_listing_types' );
		}

		if ( $primary_schema ) {
			update_post_meta( $post_id, 'schema_optional_type_1', $primary_schema );
		} else {
			delete_post_meta( $post_id, 'schema_optional_type_1' );
		}

		if ( $secondary_schema ) {
			update_post_meta( $post_id, 'schema_optional_type_2', $secondary_schema );
		} else {
			delete_post_meta( $post_id, 'schema_optional_type_2' );
		}

		if ( $tertiary_schema ) {
			update_post_meta( $post_id, 'schema_optional_type_3', $tertiary_schema );
		} else {
			delete_post_meta( $post_id, 'schema_optional_type_3' );
		}

		if ( ! empty( $properties ) && is_array( $properties ) ) {
			update_post_meta( $post_id, 'schema_properties', $properties );
		}

		return $post_id;

	}

}

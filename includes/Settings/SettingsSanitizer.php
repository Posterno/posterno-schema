<?php
/**
 * The class that holds all sanitization methods for schema properties.
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
class SettingsSanitizer {

	/**
	 * Sanitize the properties and prepare an array that is then stored into the database along with the schema.
	 *
	 * @param array $fields properties to sanitize.
	 * @return array
	 */
	public static function sanitize( $fields ) {

		$sanitized_properties = [];

		foreach ( $fields as $property_id => $property ) {

			if ( ! isset( $property['value'] ) || isset( $property['value'] ) && empty( $property['value'] ) ) {
				continue;
			}

			$property_id       = sanitize_text_field( $property_id );
			$assigned_field_id = absint( $property['value'] );

			if ( $property_id && $assigned_field_id ) {
				$sanitized_properties[ $property_id ] = $assigned_field_id;
			}
		}

		return $sanitized_properties;

	}

}

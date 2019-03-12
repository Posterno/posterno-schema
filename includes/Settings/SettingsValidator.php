<?php
/**
 * The class that holds all the settings for each schema type.
 *
 * @package     posterno_schema
 * @copyright   Copyright (c) 2018, Pressmodo, LLC
 * @license     http://opensource.org/licenses/gpl_2.0.php GNU Public License
 */

namespace PNO\SchemaOrg\Settings;

use WP_Error;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Handles validation of fields submitted through the schema editor.
 */
class SettingsValidator {

	/**
	 * Verify that required fields are filled.
	 *
	 * @param array $fields the fields that have been submitted.
	 * @return mixed
	 */
	public static function verify_required_fields( $fields ) {

		$valid = true;

		if ( ! $fields || ! is_array( $fields ) ) {
			return new WP_Error( 'missingarg', esc_html__( 'Something went wrong while validating the settings.' ) );
		}

		foreach ( $fields as $property_id => $property ) {
			if ( isset( $property['required'] ) && $property['required'] === 'true' ) {
				if ( ! isset( $property['value'] ) || isset( $property['value'] ) && empty( $property['value'] ) ) {
					return new WP_Error( 'required', esc_html__( 'One or more required fields have not been configured. Please configure all required fields.' ) );
				}
			}
		}

		return $valid;

	}

}

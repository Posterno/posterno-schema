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

	public static function verify_assigned_field_type_matches( $fields ) {

		$valid = true;

		if ( ! $fields || ! is_array( $fields ) ) {
			return new WP_Error( 'missingarg', esc_html__( 'Something went wrong while validating the settings.' ) );
		}

		foreach ( $fields as $property_id => $property ) {

			$property_label    = esc_html( $property['label'] );
			$required_type     = esc_html( $property['type'] );
			$assigned_field_id = isset( $property['value'] ) && ! empty( $property['value'] ) ? absint( $property['value'] ) : false;

			if ( $assigned_field_id ) {

				$listing_field      = new \PNO\Field\Listing( $assigned_field_id );
				$listing_field_name = $listing_field->get_name();
				$listing_field_type = $listing_field->get_type();

				if ( $listing_field->get_post_id() > 0 ) {

					if ( $required_type !== $listing_field_type ) {

						$message = sprintf(
							__( 'Property "%1$s" requires a field type of "%2$s" but the assigned listing field (%3$s) type is "%4$s". Please assign an "%2$s" type of field.' ),
							$property_label,
							$required_type,
							$listing_field_name,
							$listing_field_type
						);

						return new WP_Error( 'schema-field-type-error', $message );

					}
				}
			}
		}

		return $valid;

	}

}

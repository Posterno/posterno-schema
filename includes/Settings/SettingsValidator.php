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

	/**
	 * Verify that the fielda assigned to the schema property is among one of the allowed ones for that property.
	 *
	 * @param array $fields properties to verify.
	 * @return boolean|WP_Error
	 */
	public static function verify_assigned_field_type_matches( $fields ) {

		$valid = true;

		if ( ! $fields || ! is_array( $fields ) ) {
			return new WP_Error( 'missingarg', esc_html__( 'Something went wrong while validating the settings.' ) );
		}

		foreach ( $fields as $property_id => $property ) {

			$property_label = sanitize_text_field( $property['label'] );
			$required_type  = '';

			if ( isset( $property['type'] ) && is_array( $property['type'] ) ) {
				$required_type = array_map( 'sanitize_text_field', $property['type'] );
			} else {
				$required_type = sanitize_text_field( $property['type'] );
			}

			$assigned_field_id = isset( $property['value'] ) && ! empty( $property['value'] ) ? sanitize_text_field( $property['value'] ) : false;

			if ( $assigned_field_id && $property_label && $required_type ) {

				$registered_field_types = pno_get_registered_field_types();
				$invalid                = false;
				$message                = false;

				foreach ( $required_type as $single_type ) {
					if ( isset( $registered_field_types[ $single_type ] ) ) {
						$human_types_labels[] = $registered_field_types[ $single_type ];
					}
				}

				$human_types_labels = implode( ', ', $human_types_labels );

				// Validation process for when a custom field ID is passed.
				if ( is_numeric( $assigned_field_id ) ) {

					$listing_field      = new \PNO\Field\Listing( $assigned_field_id );
					$listing_field_name = $listing_field->get_name();
					$listing_field_type = $listing_field->get_type();

					if ( $listing_field->get_post_id() > 0 ) {

						if ( is_array( $required_type ) && ! in_array( $listing_field_type, $required_type ) ) {

							$invalid = true;

							$message = sprintf(
								__( 'Property "%1$s" requires a field type of "%2$s" but the assigned field (%3$s) type is "%4$s". Please adjust the type of field.' ),
								$property_label,
								$human_types_labels,
								$listing_field_name,
								$registered_field_types[ $listing_field_type ]
							);

						} elseif ( ! is_array( $required_type ) && $required_type !== $listing_field_type ) {

							$invalid = true;

							$message = sprintf(
								__( 'Property "%1$s" requires a field type of "%2$s" but the assigned field (%3$s) type is "%4$s". Please assign a "%2$s" type of field.' ),
								$property_label,
								$registered_field_types[ $required_type ],
								$listing_field_name,
								$registered_field_types[ $listing_field_type ]
							);
						}
					}
				} else {

					$types_allowed_for_meta_field = SettingsCollection::get_type_for_meta_field( $assigned_field_id );
					$types_sent_through           = $required_type;
					$matches                      = ( $types_allowed_for_meta_field === $types_sent_through );

					if ( ! $matches ) {
						$invalid = true;
						$message = sprintf(
							__( 'Property "%1$s" requires a field type of "%2$s". Please adjust the type of field.' ),
							$property_label,
							$human_types_labels
						);

					}
				}

				if ( $invalid && $message ) {
					return new WP_Error( 'property-type-validation-error', $message );
				}
			}
		}

		return $valid;

	}

}

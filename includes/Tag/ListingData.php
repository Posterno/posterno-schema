<?php
/**
 * Handles data retrieval for schema properties.
 *
 * @package     posterno_schema
 * @copyright   Copyright (c) 2018, Pressmodo, LLC
 * @license     http://opensource.org/licenses/gpl_2.0.php GNU Public License
 */

namespace PNO\SchemaOrg\Tag;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Retrieve data belonging to fields in the database ready to be parsed
 * for schema properties.
 */
class ListingData {

	/**
	 * Retrieve data of a given listing field.
	 *
	 * @param string $listing_id listing id.
	 * @param string $field_type type of field.
	 * @param string $meta_key database meta key.
	 * @param string $prop the property name.
	 * @return boolean|string
	 */
	public static function get( $listing_id, $field_type, $meta_key, $prop ) {

		$data = false;

		if ( ! $listing_id || ! $field_type || ! $meta_key ) {
			return;
		}

		switch ( $field_type ) {
			case 'text':
				$data = self::get_text_data( $listing_id, $meta_key );
				break;
			case 'file':
				$data = self::get_file_data( $listing_id, $meta_key );
				break;
			case 'listing-location':
				$data = self::get_location_data( $listing_id, $meta_key, $prop );
				break;
			default:
				$data = carbon_get_post_meta( $listing_id, $meta_key );
				break;
		}

		return $data;

	}

	public static function get_text_data( $listing_id, $meta_key ) {

		$data = false;

		switch ( $meta_key ) {
			case 'listing_title':
				$data = get_the_title( $listing_id );
				break;
			default:
				$data = carbon_get_post_meta( $listing_id, $meta_key );
				break;
		}

		return $data;

	}

	public static function get_file_data( $listing_id, $meta_key ) {

		$data = false;

		switch ( $meta_key ) {
			case 'listing_featured_image':
				$data = get_the_post_thumbnail_url( $listing_id, 'full' );
				break;
			default:
				$data = carbon_get_post_meta( $listing_id, $meta_key );
				break;
		}

		return $data;

	}

	public static function get_location_data( $listing_id, $meta_key, $prop ) {

		$data    = false;
		$address = carbon_get_post_meta( $listing_id, $meta_key );

		switch ( $prop ) {
			case 'location_street':
				$data = isset( $address['address'] ) ? $address['address'] : false;
				break;
		}

		return $data;

	}

}

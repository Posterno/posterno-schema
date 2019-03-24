<?php
/**
 * Handles data retrieval for static schema properties.
 *
 * @package     posterno_schema
 * @copyright   Copyright (c) 2018, Pressmodo, LLC
 * @license     http://opensource.org/licenses/gpl_2.0.php GNU Public License
 */

namespace PNO\SchemaOrg\Tag;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Retrieve data belonging to static fields.
 */
class StaticData {

	/**
	 * Get static data.
	 *
	 * Static data is intended to be either site details or listing's data
	 * that does not match the original field object meta key hence needs to be adjusted.
	 *
	 * @param string $object_id the id of the object.
	 * @param string $meta_key the key of the data to retrieve.
	 * @return mixed
	 */
	public static function get( $object_id, $meta_key ) {

		$data = false;

		switch ( $meta_key ) {
			case 'listing_url':
				$data = esc_url( get_permalink( $object_id ) );
				break;
			case 'listing_author_name':
				$listing_author = pno_get_listing_author( $object_id );
				$data           = pno_get_user_fullname( $listing_author );
				break;
			case 'listing_author_first_name':
				$listing_author = pno_get_listing_author( $object_id );
				$data           = pno_get_user_first_name( $listing_author );
				break;
			case 'listing_author_last_name':
				$listing_author = pno_get_listing_author( $object_id );
				$data           = pno_get_user_last_name( $listing_author );
				break;
			case 'listing_publish_date':
				$data = pno_get_the_listing_publish_date( $object_id );
				break;
			case 'listing_modified_date':
				$data = pno_get_listing_last_modified_date( $object_id );
				break;
			case 'listing_lon':
			case 'listing_lat':
			case 'listing_street_address':
			case 'listing_geocoded_country':
			case 'listing_geocoded_city':
			case 'listing_geocoded_region':
				$data = self::get_listing_location_data( $object_id, $meta_key );
				break;
			case 'site_title':
				$data = get_bloginfo( 'name' );
				break;
			case 'site_url':
				$data = esc_url( home_url() );
				break;
		}

		return wp_strip_all_tags( $data );

	}

	/**
	 * Get location related data.
	 *
	 * @param string $object_id listing id.
	 * @param string $meta_key the key of the data we need.
	 * @return mixed
	 */
	public static function get_listing_location_data( $object_id, $meta_key ) {

		$coordinates = pno_get_listing_coordinates( $object_id );

		$geocoded_data = get_post_meta( $object_id, 'geocoded_data', true );

		$data = false;

		switch ( $meta_key ) {
			case 'listing_lon':
				$data = isset( $coordinates['lng'] ) ? floatval( $coordinates['lng'] ) : false;
				break;
			case 'listing_lat':
				$data = isset( $coordinates['lat'] ) ? floatval( $coordinates['lat'] ) : false;
				break;
			case 'listing_street_address':
				$data = esc_html( get_post_meta( $object_id, '_listing_location_address', true ) );
				break;
			case 'listing_geocoded_city':
				$data = ! empty( $geocoded_data ) && is_array( $geocoded_data ) && isset( $geocoded_data['city'] ) ? esc_html( $geocoded_data['city'] ) : false;
				break;
			case 'listing_geocoded_region':
				$data = ! empty( $geocoded_data ) && is_array( $geocoded_data ) && isset( $geocoded_data['state_long'] ) ? esc_html( $geocoded_data['state_long'] ) : false;
				break;
			case 'listing_geocoded_country':
				$data = ! empty( $geocoded_data ) && is_array( $geocoded_data ) && isset( $geocoded_data['country_long'] ) ? esc_html( $geocoded_data['country_long'] ) : false;
				break;
		}

		return $data;

	}

}

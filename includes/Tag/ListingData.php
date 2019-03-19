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
	 * @param string $meta_key database meta key.
	 * @return mixed
	 */
	public static function get( $listing_id, $meta_key ) {

		$data = false;

		$meta_key = pno_get_string_between( $meta_key, '%_', '_%' );
		$field    = new \PNO\Database\Queries\Listing_Fields();
		$query    = $field->get_item_by( 'listing_meta_key', $meta_key );

		if ( $query instanceof \PNO\Field\Listing ) {

			switch ( $query->get_type() ) {
				case 'text':
					$data = self::get_text_data( $listing_id, $meta_key );
					break;
				case 'file':
					$data = self::get_file_data( $listing_id, $meta_key );
					break;
				default:
					$data = carbon_get_post_meta( $listing_id, $meta_key );
					break;
			}
		} else {

			$data = self::get_static_data( $listing_id, $meta_key );

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
				$data  = false;
				$image = get_the_post_thumbnail_url( $listing_id, 'full' );
				if ( $image ) {
					$data = [ $image ];
				}
				break;
			case 'listing_gallery':
				$images      = get_post_meta( $listing_id, '_listing_gallery_images', true );
				$data        = false;
				$img_sources = [];
				if ( ! empty( $images ) && is_array( $images ) ) {
					foreach ( $images as $img ) {
						$attachment = wp_get_attachment_url( $img['value'] );
						if ( $attachment ) {
							$img_sources[] = $attachment;
						}
					}
					if ( ! empty( $img_sources ) ) {
						$data = $img_sources;
					}
				}
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

	public static function get_static_data( $listing_id, $meta_key ) {

		$data = false;

		switch ( $meta_key ) {
			case 'listing_url':
				$data = get_permalink( $listing_id );
				break;
			case 'listing_author_name':
				$listing_author = pno_get_listing_author( $listing_id );
				$data           = pno_get_user_fullname( $listing_author );
				break;
			case 'listing_author_first_name':
				$listing_author = pno_get_listing_author( $listing_id );
				$data           = pno_get_user_first_name( $listing_author );
				break;
			case 'listing_author_last_name':
				$listing_author = pno_get_listing_author( $listing_id );
				$data           = pno_get_user_last_name( $listing_author );
				break;
			case 'listing_publish_date':
				$data = pno_get_the_listing_publish_date( $listing_id );
				break;
			case 'listing_modified_date':
				$data = pno_get_listing_last_modified_date( $listing_id );
				break;
			case 'site_title':
				$data = get_bloginfo( 'name' );
				break;
			case 'site_url':
				$data = home_url();
				break;
		}

		return $data;

	}

	public static function get_hours_data( $listing_id, $meta_key ) {

		$data = false;
		$sets = [];

		$business_hours = new \PNO\Listing\BusinessHours( $listing_id );

		if ( $business_hours->has_business_hours() ) {

			$opening_hours = $business_hours->get_opening_hours();

			foreach ( $opening_hours as $set ) {
				if ( ! $set->to_string() ) {
					continue;
				}
				$sets[ esc_html( $set->get_day_name() ) ] = [
					'opens'  => $set->start_time,
					'closes' => $set->start_time,
				];
			}

			if ( ! empty( $sets ) ) {
				return $sets;
			}
		}

		return $data;

	}

}

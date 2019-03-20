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
				case 'email':
				case 'password':
				case 'number':
					$data = self::get_text_data( $listing_id, $meta_key );
					break;
				case 'textarea':
				case 'editor':
					$data = self::get_textarea_data( $listing_id, $meta_key );
					break;
				case 'checkbox':
					$data = self::get_boolean_data( $listing_id, $meta_key );
					break;
				case 'select':
				case 'radio':
					$data = self::get_dropdown_value( $listing_id, $meta_key, $query );
					break;
				case 'multiselect':
				case 'multicheckbox':
					$data = self::get_multidropdown_value( $listing_id, $meta_key, $query );
					break;
				case 'url':
					$data = self::get_url_data( $listing_id, $meta_key );
					break;
				case 'file':
					$data = self::get_file_data( $listing_id, $meta_key );
					break;
				case 'listing-opening-hours':
					$data = self::get_hours_data( $listing_id );
					break;
				default:
					$data = esc_html( carbon_get_post_meta( $listing_id, $meta_key ) );
					break;
			}
		} else {

			$data = StaticData::get( $listing_id, $meta_key );

		}

		/**
		 * Filter: allows customization of schema properties values.
		 *
		 * @param mixed              $data originally found listing property value.
		 * @param string             $meta_key the key of the field being looked up.
		 * @param \PNO\Field\Listing $query optional listing field object that may be found.
		 * @param string             $listing_id the id of the listing currently being analyzed.
		 * @return mixed
		 */
		return apply_filters( 'pno_listing_schema_property_data', $data, $meta_key, $query, $listing_id );

	}

	/**
	 * Get data belonging to a text field.
	 *
	 * @param string $listing_id id of the listing.
	 * @param string $meta_key meta key.
	 * @return mixed
	 */
	public static function get_text_data( $listing_id, $meta_key ) {

		$data = false;

		switch ( $meta_key ) {
			case 'listing_title':
				$data = get_the_title( $listing_id );
				break;
			case 'listing_email_address':
				$data = carbon_get_post_meta( $listing_id, 'listing_email' );
				break;
			default:
				$data = carbon_get_post_meta( $listing_id, $meta_key );
				break;
		}

		return esc_html( $data );

	}

	/**
	 * Get data belonging to a textarea field.
	 *
	 * @param string $listing_id id of the listing.
	 * @param string $meta_key meta key.
	 * @return string
	 */
	public static function get_textarea_data( $listing_id, $meta_key ) {
		return wp_strip_all_tags( carbon_get_post_meta( $listing_id, $meta_key ) );
	}

	/**
	 * Get data belonging to a boolean field.
	 *
	 * @param string $listing_id id of the listing.
	 * @param string $meta_key meta key.
	 * @return string
	 */
	public static function get_boolean_data( $listing_id, $meta_key ) {
		return (bool) carbon_get_post_meta( $listing_id, $meta_key );
	}

	/**
	 * Get data belonging to a dropdown field.
	 *
	 * @param string             $listing_id id of the listing.
	 * @param string             $meta_key meta key.
	 * @param \PNO\Field\Listing $field field.
	 * @return string
	 */
	public static function get_dropdown_value( $listing_id, $meta_key, $field ) {

		$value = carbon_get_post_meta( $listing_id, $meta_key );
		$data  = pno_display_field_select_value( $value, [ 'options' => $field->get_options() ] );

		return $data;

	}

	/**
	 * Get data belonging to a dropdown field.
	 *
	 * @param string             $listing_id id of the listing.
	 * @param string             $meta_key meta key.
	 * @param \PNO\Field\Listing $field field.
	 * @return string
	 */
	public static function get_multidropdown_value( $listing_id, $meta_key, $field ) {

		$value = carbon_get_post_meta( $listing_id, $meta_key );
		$data  = pno_display_field_multicheckbox_value( $value, [ 'options' => $field->get_options() ] );

		return $data;

	}

	/**
	 * Get data belonging to an url field.
	 *
	 * @param string $listing_id id of the listing.
	 * @param string $meta_key meta key.
	 * @return string
	 */
	public static function get_url_data( $listing_id, $meta_key ) {

		$data = false;

		switch ( $meta_key ) {
			case 'listing_video':
				$data = carbon_get_post_meta( $listing_id, 'listing_media_embed' );
				break;
			default:
				$data = carbon_get_post_meta( $listing_id, $meta_key );
				break;
		}

		return esc_url( $data );
	}

	/**
	 * Get data belonging to a file field.
	 *
	 * @param string $listing_id id of the listing.
	 * @param string $meta_key meta key.
	 * @return mixed
	 */
	public static function get_file_data( $listing_id, $meta_key ) {

		$data = false;

		switch ( $meta_key ) {
			case 'listing_featured_image':
				$data  = false;
				$image = get_the_post_thumbnail_url( $listing_id, 'full' );
				if ( $image ) {
					$data = [ esc_url( $image ) ];
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
							$img_sources[] = esc_url( $attachment );
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

	/**
	 * Get data belonging to the business hours field.
	 *
	 * @param string $listing_id id of the listing.
	 * @return array
	 */
	public static function get_hours_data( $listing_id ) {

		$data = false;
		$sets = [];

		$business_hours = new \PNO\Listing\BusinessHours( $listing_id );

		if ( $business_hours->has_business_hours() ) {

			$opening_hours = $business_hours->get_opening_hours();

			foreach ( $opening_hours as $set ) {
				if ( ! $set->start_time ) {
					continue;
				}
				$sets[] = [
					'@type'     => 'OpeningHoursSpecification',
					'dayOfWeek' => esc_html( $set->get_day_name() ),
					'opens'     => esc_html( $set->start_time ),
					'closes'    => esc_html( $set->start_time ),
				];
			}

			if ( ! empty( $sets ) ) {
				return $sets;
			}
		}

		return $data;

	}

}

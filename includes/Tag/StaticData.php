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

	public static function get( $object_id, $meta_key ) {

		$data = false;

		switch ( $meta_key ) {
			case 'listing_url':
				$data = get_permalink( $object_id );
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
			case 'site_title':
				$data = get_bloginfo( 'name' );
				break;
			case 'site_url':
				$data = home_url();
				break;
		}

		return $data;

	}

}

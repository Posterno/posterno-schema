<?php
/**
 * Functions used within the admin panel only.
 *
 * @package     posterno-schema
 * @copyright   Copyright (c) 2018, Pressmodo, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.1.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Retrieve the list of schemas allowed to be used.
 *
 * @return array
 */
function pno_get_allowed_schemas() {

	$list = [
		'Article',
		'Book',
		'Course',
		'Event',
		'JobPosting',
		'LocalBusiness',
		'Person',
		'Product',
		'Service',
		'SoftwareApplication',
		'VideoObject',
	];

	return $list;

}

/**
 * Retrieve an helper link for a given schema.
 *
 * @param string $schema the schema for which to retrieve the url.
 * @return string
 */
function pno_get_schema_url( $schema ) {

	$urls = [
		'Article'             => 'https://developers.google.com/search/docs/data-types/article',
		'Book'                => 'https://developers.google.com/search/docs/data-types/book',
		'Course'              => 'https://developers.google.com/search/docs/data-types/course',
		'Event'               => 'https://developers.google.com/search/docs/data-types/event',
		'JobPosting'          => 'https://developers.google.com/search/docs/data-types/job-posting',
		'LocalBusiness'       => 'https://developers.google.com/search/docs/data-types/local-business',
		'Review'              => 'https://developers.google.com/search/docs/data-types/review-snippet',
		'Person'              => 'https://schema.org/Person',
		'Product'             => 'https://developers.google.com/search/docs/data-types/product',
		'Recipe'              => 'https://developers.google.com/search/docs/data-types/recipe',
		'Service'             => 'https://schema.org/Service',
		'SoftwareApplication' => 'https://developers.google.com/search/docs/data-types/software-app',
		'VideoObject'         => 'https://developers.google.com/search/docs/data-types/video',
	];

	$url = false;

	if ( isset( $urls[ $schema ] ) ) {
		$url = $urls[ $schema ];
	}

	return $url;

}

/**
 * Retrieve example json file for one of the schemas available.
 *
 * @param string $schema the schema for which we're looking an example file.
 * @return boolean|object
 */
function pno_get_schema_example_json( $schema ) {

	$json = false;

	$directory = trailingslashit( PNO_SCHEMA_URL ) . 'examples/';

	$url = $directory;

	$schema = strtolower( $schema );

	switch ( $schema ) {
		case 'jobposting':
			$url .= 'job.json';
			break;
		case 'localbusiness':
			$url .= 'place.json';
			break;
		case 'softwareapplication':
			$url .= 'software.json';
			break;
		case 'videoobject':
			$url .= 'video.json';
			break;
		default:
			$url .= "{$schema}.json";
			break;
	}

	$request = wp_remote_get( $url );

	if ( is_wp_error( $request ) ) {
		return false;
	}

	$body = wp_remote_retrieve_body( $request );
	$json = json_decode( $body );

	return $json;

}

/**
 * Retrieve a list of all listings fields available for schemas.
 *
 * @return array
 */
function pno_get_schema_listings_fields() {

	$fields = [];

	$listing_fields = new \PNO\Database\Queries\Listing_Fields( [ 'number' => 999 ] );

	if ( ! empty( $listing_fields->items ) && is_array( $listing_fields->items ) ) {
		$custom_fields_ids = [];
		foreach ( $listing_fields->items as $field ) {
			$custom_fields_ids[] = $field->get_post_id();
		}
		foreach ( $custom_fields_ids as $listing_field_id ) {
			$custom_listing_field        = new \PNO\Field\Listing( $listing_field_id );
			$fields[ $listing_field_id ] = [
				'type' => $custom_listing_field->get_type(),
				'name' => $custom_listing_field->get_name(),
				'meta' => $custom_listing_field->get_object_meta_key(),
			];
		}
	}

	// Add static fields.
	$fields['listing_url']               = [
		'type' => 'url',
		'name' => esc_html__( 'Listing URL' ),
		'meta' => 'listing_url',
	];
	$fields['listing_author_name']       = [
		'type' => 'text',
		'name' => esc_html__( 'Listing author name' ),
		'meta' => 'listing_author_name',
	];
	$fields['listing_author_first_name'] = [
		'type' => 'text',
		'name' => esc_html__( 'Listing author first name' ),
		'meta' => 'listing_author_first_name',
	];
	$fields['listing_author_last_name']  = [
		'type' => 'text',
		'name' => esc_html__( 'Listing author last name' ),
		'meta' => 'listing_author_last_name',
	];
	$fields['listing_publish_date']      = [
		'type' => 'date',
		'name' => esc_html__( 'Listing publish date' ),
		'meta' => 'listing_publish_date',
	];
	$fields['listing_modified_date']     = [
		'type' => 'date',
		'name' => esc_html__( 'Listing last modified date' ),
		'meta' => 'listing_modified_date',
	];

	$fields['site_title'] = [
		'type' => 'text',
		'name' => esc_html__( 'Site title' ),
		'meta' => 'site_title',
	];

	$fields['site_url'] = [
		'type' => 'url',
		'name' => esc_html__( 'Site URL' ),
		'meta' => 'site_url',
	];

	return $fields;

}

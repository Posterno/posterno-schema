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
 * Loop through the response from the schema.org api and prepare a formatted array.
 *
 * @param array $items the items from the api response.
 * @return array
 */
function pno_generate_schema_hierarchy( $items ) {

	$return = [];

	foreach ( $items as $key => $item ) {

		$prop = '@id';

		$return[ $key ] = [
			'id'    => $item->$prop,
			'label' => $item->name,
		];

		if ( isset( $item->children ) && count( $item->children ) > 0 ) {
			$return[ $key ]['children'] = pno_generate_schema_hierarchy( $item->children );
		}
	}

	return $return;

}

/**
 * Query the schema.org api and cache the formatted response.
 *
 * @return array
 */
function pno_get_schema_hierarchy() {

	$schemas = remember_transient(
		'pno_schema_hierarchy',
		function () {

			$url     = 'https://schema.org/docs/tree.jsonld';
			$request = wp_remote_get( $url );

			if ( is_wp_error( $request ) ) {
				return;
			}

			$response = wp_remote_retrieve_body( $request );
			$response = json_decode( $response );

			return pno_generate_schema_hierarchy( $response->children );

		},
		WEEK_IN_SECONDS
	);

	return $schemas;

}

/**
 * Get the first direct children of a given schema.
 *
 * @param string $schema schema name.
 * @return array
 */
function pno_get_schema_direct_children( $schema ) {

	$children = [];

	$schemas = pno_get_schema_hierarchy();

	$children = wp_list_filter( $schemas, [ 'label' => $schema ] );

	return $children;

}

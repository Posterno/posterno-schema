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
 * Get all the properties of a given schema.
 *
 * @param array $schema schema types for which we're going to retrieve properties.
 * @return array
 */
function pno_get_schema_properties( $schema = [] ) {

	$all_properties = pno_get_schema_properties_list();

	$schema_properties = [];

	if ( is_array( $schema ) && ! empty( $schema ) ) {
		foreach ( $all_properties as $propkey => $property ) {
			foreach ( $schema as $type ) {
				if ( in_array( $type, $property['types'] ) ) {
					if ( ! isset( $schema_properties[ $propkey ] ) ) {
						$schema_properties[ $propkey ] = [
							'name' => $propkey,
							'url'  => $property['url'],
						];
					}
				}
			}
		}
	}

	return $schema_properties;

}

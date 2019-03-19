<?php
/**
 * Handles injection of the json-ld tag within listings pages.
 *
 * @package     posterno_schema
 * @copyright   Copyright (c) 2018, Pressmodo, LLC
 * @license     http://opensource.org/licenses/gpl_2.0.php GNU Public License
 */

namespace PNO\SchemaOrg\Tag;

use WP_Query;
use PNO\SchemaOrg\Graph;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Load structured data tag when required within listings pages.
 */
class Listing {

	/**
	 * Hook into WordPress.
	 *
	 * @return void
	 */
	public function init() {

		add_action( 'wp_footer', [ $this, 'inject_tag' ] );

	}

	/**
	 * Determine if we're on a page where we should load the jsonld tags.
	 *
	 * @return boolean
	 */
	private function should_load() {

		return is_singular( [ 'listings' ] );

	}

	/**
	 * Retrive all listings global schemas.
	 *
	 * @return array
	 */
	private function get_global_schemas() {

		$schemas = [];

		$args = [
			'post_type'              => 'pno_schema',
			'posts_per_page'         => 100,
			'nopaging'               => true,
			'no_found_rows'          => true,
			'update_post_term_cache' => false,
			'post_status'            => 'publish',
			'suppress_filters'       => true,
			'fields'                 => 'ids',
			'meta_query'             => array(
				array(
					'key'   => 'schema_mode',
					'value' => 'global',
				),
			),

		];

		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {

			while ( $query->have_posts() ) {

				$query->the_post();

				$post_id    = get_the_id();
				$properties = get_post_meta( $post_id, 'schema_properties', true );
				$properties = $this->parse_properties( $properties );

				if ( is_array( $properties ) && ! empty( $properties ) ) {
					$schemas[] = [
						'post_id'    => $post_id,
						'properties' => $properties,
					];
				}
			}
		}

		wp_reset_postdata();

		return $schemas;

	}

	/**
	 * Retrive details of the field assigned to a property before we actually retrieve it's value.
	 *
	 * @param array $properties properties to parse.
	 * @return array
	 */
	private function parse_properties( $properties ) {

		$parsed = [];

		if ( is_array( $properties ) && ! empty( $properties ) ) {

			foreach ( $properties as $prop => $field_id ) {

				if ( is_numeric( $field_id ) ) {

					$field = new \PNO\Field\Listing( $field_id );

					if ( $field->get_post_id() > 0 ) {
						$parsed[ $prop ] = [
							'type'     => $field->get_type(),
							'meta_key' => $field->get_object_meta_key(),
						];
					}
				} else {

					$parsed[ $prop ] = [
						'type'     => 'static',
						'meta_key' => $field_id,
					];

				}
			}
		}

		return $parsed;

	}

	/**
	 * Attach listing values to schema properties.
	 *
	 * @param array  $schema the schema to parse.
	 * @param string $listing_id the listing id for which we're grabbing the values.
	 * @return array
	 */
	private function attach_values_to_schema( $schema, $listing_id ) {

		$parsed_schema     = $schema;
		$parsed_properties = [];

		foreach ( $schema['properties'] as $prop => $field ) {

			$meta_key = $field['meta_key'];
			$type     = $field['type'];

			$value = ListingData::get( $listing_id, $type, $meta_key, $prop );

			if ( $value ) {
				$prop                       = Renamer::rename( $prop );
				$parsed_properties[ $prop ] = $value;
			}
		}

		if ( ! empty( $parsed_properties ) ) {
			$parsed_schema['properties'] = $parsed_properties;
		}

		return $parsed_schema;

	}

	/**
	 * Load the structured data tag within the footer.
	 *
	 * @return void
	 */
	public function inject_tag() {

		if ( ! $this->should_load() ) {
			return;
		}

		$global_schemas = $this->get_global_schemas();
		$parsed_schemas = [];

		$listing_id = get_queried_object_id();

		if ( ! empty( $global_schemas ) && is_array( $global_schemas ) ) {
			foreach ( $global_schemas as $schema ) {

				$schema = $this->attach_values_to_schema( $schema, $listing_id );

				if ( $schema ) {
					$parsed_schemas[] = $schema;
				}
			}
		}

		print_r( $parsed_schemas );

	}

}

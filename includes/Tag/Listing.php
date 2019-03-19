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

				$post_id = get_the_id();
				$code    = get_post_meta( $post_id, 'schema_code', true );
				$code    = $this->parse_json( $code );

				if ( $post_id && $code ) {
					$schemas[] = [
						'schema_post_id' => $post_id,
						'code'           => $code,
					];
				}
			}
		}

		wp_reset_postdata();

		return $schemas;

	}

	/**
	 * Parse retrieved json and retrieve listing data to attach.
	 *
	 * @param string $json json string stored into a schema.
	 * @return string
	 */
	private function parse_json( $json ) {

		$data = json_decode( stripslashes( $json ), true );

		array_walk_recursive(
			$data,
			function( &$property_value, $key ) {
				if ( $this->is_dynamic_field( $property_value ) ) {
					$property_value = 'h';
				}
			}
		);

		print_r( $data );
		exit;

		return wp_json_encode( $data );

	}

	/**
	 * Verify a given key, is a dynamic field key placeholder for schemas properties.
	 *
	 * @param string $key the key to verify
	 * @return boolean
	 */
	private function is_dynamic_field( $key ) {
		return pno_starts_with( $key, '%_' ) && pno_ends_with( $key, '_%' );
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

		print_r( $global_schemas );

		$parsed_schemas = [];

		$listing_id = get_queried_object_id();

	}

}

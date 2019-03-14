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

				if ( is_array( $properties ) && ! empty( $properties ) ) {
					$schemas[] = [
						'post_id'    => $post_id,
						'schema_id'  => get_post_meta( $post_id, 'schema_name', true ),
						'properties' => get_post_meta( $post_id, 'schema_properties', true ),
					];
				}
			}
		}

		wp_reset_postdata();

		return $schemas;

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

	}

}

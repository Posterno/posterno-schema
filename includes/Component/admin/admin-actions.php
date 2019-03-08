<?php
/**
 * Hooks for the admin panel.
 *
 * @package     posterno-schema
 * @copyright   Copyright (c) 2018, Pressmodo, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.1.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Create new listings schema via ajax.
 *
 * @return void
 */
function pno_ajax_create_listing_schema() {

	check_ajax_referer( 'pno_create_listing_schema', 'nonce' );

	$schema_id       = false;
	$general_message = esc_html__( 'Something went wrong: could not create new schema.' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( $general_message, 403 ); //phpcs:ignore
	}

	$mode   = isset( $_POST['mode'] ) && ! empty( $_POST['mode'] ) ? sanitize_text_field( $_POST['mode'] ) : false;
	$schema = isset( $_POST['schema'] ) && ! empty( $_POST['schema'] ) ? sanitize_text_field( $_POST['schema'] ) : false;
	$types  = isset( $_POST['types'] ) && is_array( $_POST['types'] ) ? array_map( 'absint', $_POST['types'] ) : [];

	if ( in_array( $schema, pno_get_schema_list() ) && $mode ) {

		$args = array(
			'post_title'  => $schema,
			'post_status' => 'publish',
			'post_type'   => 'pno_schema',
		);

		$schema_id = wp_insert_post( $args );

	} else {

		wp_die( $general_message, 403 ); //phpcs:ignore

	}

	if ( $schema_id ) {

		wp_send_json_success( $schema_id );

	} else {

		wp_die( $general_message, 403 ); //phpcs:ignore

	}

}
add_action( 'wp_ajax_pno_create_listing_schema', 'pno_ajax_create_listing_schema' );

function pno_ajax_get_listings_schemas_list() {

	check_ajax_referer( 'pno_get_listings_schema', 'nonce' );

	$general_message = esc_html__( 'Something went wrong: could not get schema list.' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( $general_message, 403 ); //phpcs:ignore
	}

	$args = [
		'post_type'              => 'pno_schema',
		'posts_per_page'         => 9999,
		'nopaging'               => true,
		'no_found_rows'          => true,
		'update_post_term_cache' => false,
		'post_status'            => 'publish',
		'suppress_filters'       => true,
	];

	$schemas = new WP_Query( $args );

	$found_schemas = [];

	if ( $schemas->have_posts() ) {

		while ( $schemas->have_posts() ) {

			$schemas->the_post();

			$id   = get_the_id();
			$mode = get_post_meta( $id, 'schema_mode', true ) === 'type' ? esc_html__( 'Specific listing type(s)' ) : esc_html__( 'All listings' );

			$found_schemas[] = [
				'name'          => get_the_title(),
				'mode'          => $mode,
				'listing_types' => '—',
				'id'            => get_the_id(),
			];

		}
	}

	wp_reset_postdata();

	wp_send_json_success( [ 'schemas' => $found_schemas ] );

}
add_action( 'wp_ajax_pno_get_listings_schema_list', 'pno_ajax_get_listings_schemas_list' );

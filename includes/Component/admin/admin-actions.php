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

		update_post_meta( $schema_id, 'schema_mode', $mode );
		update_post_meta( $schema_id, 'schema_name', $schema );

		if ( $mode === 'type' ) {
			update_post_meta( $schema_id, 'schema_listing_types', $types );
		}
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

/**
 * Retrieve schemas saved into the db.
 *
 * @return void
 */
function pno_ajax_get_listings_schemas_list() {

	check_ajax_referer( 'pno_get_listings_schemas', 'nonce' );

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

			$id          = get_the_id();
			$mode        = get_post_meta( $id, 'schema_mode', true );
			$mode_label  = $mode === 'type' ? esc_html__( 'Specific listing type(s)' ) : esc_html__( 'All listings' );
			$found_types = '—';

			if ( $mode === 'type' ) {

				$listing_types = get_post_meta( $id, 'schema_listing_types', true );
				$found_types   = [];

				if ( ! empty( $listing_types ) && is_array( $listing_types ) ) {

					foreach ( $listing_types as $type ) {
						$type_term = get_term_by( 'id', $type, 'listings-types' );
						if ( $type_term instanceof WP_Term ) {
							$found_types[] = $type_term->name;
						}
					}
				}

				if ( ! empty( $found_types ) ) {
					$found_types = implode( ', ', $found_types );
				}
			}

			$found_schemas[] = [
				'name'          => get_the_title(),
				'mode'          => $mode_label,
				'listing_types' => $found_types,
				'id'            => get_the_id(),
			];

		}
	}

	wp_reset_postdata();

	wp_send_json_success( [ 'schemas' => $found_schemas ] );

}
add_action( 'wp_ajax_pno_get_listings_schemas_list', 'pno_ajax_get_listings_schemas_list' );

/**
 * Retrieve details about a single listing schema.
 *
 * @return void
 */
function pno_ajax_get_listing_schema() {

	check_ajax_referer( 'pno_get_listing_schema', 'nonce' );

	$general_message = esc_html__( 'Something went wrong: could not get schema details.' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( $general_message, 403 ); //phpcs:ignore
	}

	$details = [];

	$schema_id = isset( $_GET['schema'] ) && ! empty( $_GET['schema'] ) ? absint( $_GET['schema'] ) : false;

	if ( ! $schema_id ) {
		wp_die( $general_message, 403 ); //phpcs:ignore
	}

	$args = [
		'post_type'              => 'pno_schema',
		'p'                      => $schema_id,
		'nopaging'               => true,
		'no_found_rows'          => true,
		'update_post_term_cache' => false,
		'suppress_filters'       => true,
	];

	$schema = new WP_Query( $args );

	if ( $schema->have_posts() ) {

		while ( $schema->have_posts() ) {

			$schema->the_post();

			$details = [
				'name'          => get_post_meta( $schema_id, 'schema_name', true ),
				'mode'          => get_post_meta( $schema_id, 'schema_mode', true ),
				'id'            => $schema_id,
				'title'         => get_the_title(),
				'listing_types' => get_post_meta( $schema_id, 'schema_listing_types', true ),
			];

		}
	} else {
		wp_die( $general_message, 403 ); //phpcs:ignore
	}

	wp_reset_postdata();

	wp_send_json_success( $details );

}
add_action( 'wp_ajax_pno_get_listing_schema', 'pno_ajax_get_listing_schema' );

/**
 * Retrive child schemas from a given primary schema.
 *
 * @return void
 */
function pno_ajax_get_child_schema() {

	check_ajax_referer( 'pno_get_child_schema', 'nonce' );

	$general_message = esc_html__( 'Something went wrong: could not get schema details.' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( $general_message, 403 ); //phpcs:ignore
	}

	$childs = [];

	$schema_id = isset( $_GET['schema'] ) && ! empty( $_GET['schema'] ) ? sanitize_text_field( $_GET['schema'] ) : false;

	if ( $schema_id ) {

		$childs = pno_search_in_array( pno_get_schema_hierarchy(), 'label', $schema_id );

		if ( ! empty( $childs ) ) {

			$childskey    = key( $childs );
			$childs       = $childs[ $childskey ]['children'];
			$has_children = isset( $childs['children'] ) && ! empty( $childs['children'] ) ? true : false;

		}
	} else {
		wp_die( $general_message, 403 ); //phpcs:ignore
	}

	wp_send_json_success(
		[
			'childs'       => $childs,
			'has_children' => $has_children,
		]
	);

}
add_action( 'wp_ajax_pno_get_child_schema', 'pno_ajax_get_child_schema' );

function t() {

	//print_r( pno_search_in_array( pno_get_schema_properties_list(), 'types', 'Hotel' ) );
	print_r( pno_get_schema_properties( [ 'Message' ] ) );
	exit;

}
//add_action( 'init', 't' );

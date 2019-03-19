<?php
/**
 * Hooks for the admin panel.
 *
 * @package     posterno-schema
 * @copyright   Copyright (c) 2018, Pressmodo, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.1.0
 */

use PNO\SchemaOrg\Settings\SettingsCollection;
use PNO\SchemaOrg\Settings\SettingsValidator;
use PNO\SchemaOrg\Settings\SettingsSanitizer;
use PNO\SchemaOrg\Settings\SettingsStorage;

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

	if ( in_array( $schema, pno_get_allowed_schemas() ) && $mode ) {

		$args = array(
			'post_title'  => $schema,
			'post_status' => 'publish',
			'post_type'   => 'pno_schema',
		);

		$schema_id = wp_insert_post( $args );

		$example = pno_get_schema_example_json( $schema );

		if ( $example ) {
			update_post_meta( $schema_id, 'schema_code', wp_json_encode( $example ) );
		}
	} elseif ( ! $schema && $mode ) {

		$args = array(
			'post_title'  => esc_html__( 'Custom schema' ),
			'post_status' => 'publish',
			'post_type'   => 'pno_schema',
		);

		$schema_id = wp_insert_post( $args );

		update_post_meta( $schema_id, 'schema_code', wp_json_encode( '{}' ) );

	}

	if ( $schema_id ) {

		update_post_meta( $schema_id, 'schema_mode', $mode );

		if ( $mode === 'type' ) {
			update_post_meta( $schema_id, 'schema_listing_types', $types );
		}

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

	// Get all available fields.
	$fields = pno_get_schema_listings_fields();

	if ( $schema->have_posts() ) {

		while ( $schema->have_posts() ) {

			$schema->the_post();

			$name = get_post_meta( $schema_id, 'schema_name', true );

			$details = [
				'name'          => $name,
				'mode'          => get_post_meta( $schema_id, 'schema_mode', true ),
				'id'            => $schema_id,
				'title'         => get_the_title(),
				'listing_types' => get_post_meta( $schema_id, 'schema_listing_types', true ),
				'schema_url'    => pno_get_schema_url( $name ),
				'json'          => get_post_meta( $schema_id, 'schema_code', true ),
				'fields'        => $fields,
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
 * Save listing schema in the database.
 *
 * @return void
 */
function pno_ajax_save_listing_schema() {

	check_ajax_referer( 'pno_save_listing_schema', 'nonce' );

	$general_message = esc_html__( 'Something went wrong: could not save listing schema.' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( $general_message, 403 ); //phpcs:ignore
	}

	$schema_id = isset( $_POST['post_id'] ) && ! empty( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : false;

	if ( $schema_id ) {

		$schema_details = isset( $_POST['schema'] ) && ! empty( $_POST['schema'] ) ? $_POST['schema'] : false;

		$name             = isset( $schema_details['name'] ) ? sanitize_text_field( $schema_details['name'] ) : false;
		$mode             = isset( $schema_details['mode'] ) ? sanitize_text_field( $schema_details['mode'] ) : false;
		$title            = isset( $schema_details['title'] ) ? sanitize_text_field( $schema_details['title'] ) : false;
		$listing_types    = isset( $schema_details['listing_types'] ) && is_array( $schema_details['listing_types'] ) && ! empty( $schema_details['listing_types'] ) ? array_map( 'absint', $schema_details['listing_types'] ) : false;

		if ( empty( $title ) || ! $title ) {
			wp_die( esc_html__( 'Something went wrong: the schema must have a title. Please enter a title.' ), 403 ); //phpcs:ignore
		}

		if ( $mode === 'type' && empty( $listing_types ) ) {
			wp_die( esc_html__( 'Something went wrong: select listing types or set this schema as global.' ), 403 ); //phpcs:ignore
		}
		exit;
/*
		$validation = SettingsValidator::verify_required_fields( $properties );

		if ( is_wp_error( $validation ) ) {
			wp_die( $validation->get_error_message(), 403 ); //phpcs:ignore
		}

		$type_validator = SettingsValidator::verify_assigned_field_type_matches( $properties );

		if ( is_wp_error( $type_validator ) ) {
			wp_die( $type_validator->get_error_message(), 403 ); //phpcs:ignore
		}

		$properties = SettingsSanitizer::sanitize( $properties );
		$schema     = SettingsStorage::save( $schema_id, $name, $mode, $title, $listing_types, $primary_schema, $secondary_schema, $tertiary_schema, $properties );

		if ( is_wp_error( $schema ) ) {
			wp_die( $schema->get_error_message(), 403 ); //phpcs:ignore
		}

		wp_send_json_success( $schema );*/

	} else {
		wp_die( $general_message, 403 ); //phpcs:ignore
	}

}
add_action( 'wp_ajax_pno_save_listing_schema', 'pno_ajax_save_listing_schema' );

/**
 * Delete a given schema from the database.
 *
 * @return void
 */
function pno_ajax_delete_schema() {

	check_ajax_referer( 'pno_delete_schema', 'nonce' );

	$general_message = esc_html__( 'Something went wrong: could not delete schema.' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( $general_message, 403 ); //phpcs:ignore
	}

	$schema = isset( $_POST['schema'] ) && ! empty( $_POST['schema'] ) ? absint( $_POST['schema'] ) : false;

	if ( $schema ) {

		wp_delete_post( $schema, true );

		wp_send_json_success();

	} else {
		wp_die( $general_message, 403 ); //phpcs:ignore
	}

}
add_action( 'wp_ajax_pno_delete_schema', 'pno_ajax_delete_schema' );

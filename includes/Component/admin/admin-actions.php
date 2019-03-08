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

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Something went wrong: could not create new schema.' ), 403 );
	}

	$mode   = isset( $_POST['mode'] ) && ! empty( $_POST['mode'] ) ? sanitize_text_field( $_POST['mode'] ) : false;
	$schema = isset( $_POST['schema'] ) && ! empty( $_POST['schema'] ) ? sanitize_text_field( $_POST['schema'] ) : false;
	$types  = isset( $_POST['types'] ) && is_array( $_POST['types'] ) ? array_map( 'absint', $_POST['types'] ) : [];

	wp_send_json_success();

}
add_action( 'wp_ajax_pno_create_listing_schema', 'pno_ajax_create_listing_schema' );

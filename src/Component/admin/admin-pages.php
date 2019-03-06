<?php
/**
 * Handles manipulation and registration of admin pages.
 *
 * @package     posterno-schema
 * @copyright   Copyright (c) 2018, Pressmodo, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.1.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Register admin pages for the plugin.
 *
 * @return void
 */
function pno_schema_admin_pages() {

	add_submenu_page( 'edit.php?post_type=listings', esc_html__( 'Listings Schema Settings' ), __( 'Schema' ), 'manage_options', 'posterno-listings-schema', 'pno_schema_admin_listings_schema_page' );

}
add_action( 'admin_menu', 'pno_schema_admin_pages', 10 );

/**
 * Handles loading of the listings schema page.
 * Actual output is handled via Vuejs.
 *
 * @return void
 */
function pno_schema_admin_listings_schema_page() {

	echo '<div id="pno-listings-schema"></div>';

}

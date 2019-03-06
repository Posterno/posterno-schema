<?php
/**
 * Handles manipulation and registration of admin pages.
 *
 * @package     posterno-schema
 * @copyright   Copyright (c) 2018, Pressmodo, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.1.0
 */

namespace PNO\Schema\Admin;

use Posterno\SchemaOrg\Schema;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Register admin pages for the plugin.
 *
 * @return void
 */
function admin_pages() {

	add_submenu_page( 'edit.php?post_type=listings', esc_html__( 'Listings Schema Settings' ), __( 'Schema' ), 'manage_options', 'posterno-listings-schema', __NAMESPACE__ . '\\admin_listings_schema_page' );

}
add_action( 'admin_menu', __NAMESPACE__ . '\\admin_pages', 10 );

/**
 * Handles loading of the listings schema page.
 * Actual output is handled via Vuejs.
 *
 * @return void
 */
function admin_listings_schema_page() {

	echo '<div id="pno-listings-schema"></div>';

}

function t() {

	$localBusiness = Schema::localBusiness()
		->name('Spatie')
		->email('info@spatie.be')
		->contactPoint(Schema::contactPoint()->areaServed('Worldwide'));

	$localBusiness->toArray();

	print_r( $localBusiness );

}
add_action( 'admin_init', __NAMESPACE__ . '\\t' );

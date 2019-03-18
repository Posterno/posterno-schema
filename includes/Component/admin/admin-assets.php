<?php
/**
 * Load required assets in the admin panel.
 *
 * @package     posterno-schema
 * @copyright   Copyright (c) 2018, Pressmodo, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.1.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Load assets within the admin panel.
 *
 * @return void
 */
function pno_schema_admin_assets() {

	$screen  = get_current_screen();
	$js_dir  = PNO_PLUGIN_URL . 'assets/js/admin/';
	$css_dir = PNO_PLUGIN_URL . 'assets/css/admin/';
	$version = PNO_VERSION;

	if ( defined( 'PNO_VUE_DEV' ) && PNO_VUE_DEV === true ) {

		wp_register_script( 'pno-schema-js', 'http://localhost:8081/listings-schema-editor.js', [], $version, true );

	} else {

	}

	if ( $screen->id === 'listings_page_posterno-listings-schema' ) {
		wp_enqueue_style( 'pno-editors-styling' );
		wp_enqueue_style( 'pno-jsoneditor', PNO_PLUGIN_URL . 'assets/css/third-party/jsoneditor/jsoneditor.css', false, $version );
		wp_enqueue_script( 'pno-schema-js' );
		wp_localize_script( 'pno-schema-js', 'pno_schema_editor', pno_get_schema_editor_js_vars() );
	}

}
add_action( 'admin_enqueue_scripts', 'pno_schema_admin_assets', 100 );

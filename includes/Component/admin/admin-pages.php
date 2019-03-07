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

/**
 * Retrieve the list of labels for the schema editor.
 *
 * @return array
 */
function pno_get_schema_editor_js_vars() {

	$labels = [
		'plugin_url' => PNO_PLUGIN_URL,
		'labels'     => [
			'documentation' => esc_html__( 'Documentation', 'posterno' ),
			'add'           => esc_html__( 'Add new schema' ),
			'back'          => esc_html__( 'Go back to the schema list' ),
			'setup'         => esc_html__( 'Setup new schema' ),
			'readmore' => esc_html__( 'Read more' ),
			'listing'       => [
				'title' => esc_html__( 'Posterno listings schema editor' ),
			],
			'structured'    => [
				'step1_title'        => esc_html__( 'Create new schema' ),
				'step1_description'  => esc_html__( 'Structured data is code in a specific format, written in such a way that search engines use it to display search results in a specific and much richer way.' ),
				'step1_description2' => esc_html__( 'Posterno helps you automatically markup your listings with ease through a simple click and select interface. Start by selecting the schema you wish to create.' ),
				'step1_lists'        => [
					[
						'text' => esc_html__( 'If you’re just getting started, visit the article "Understand how structured data works." on Google.com' ),
						'url'  => esc_url( 'https://developers.google.com/search/docs/guides/intro-structured-data' ),
					],
					[
						'text' => esc_html__( 'Explore the search gallery on Google.com to see how Google uses structured data to display search results in a richer way.' ),
						'url'  => esc_url( 'https://developers.google.com/search/docs/guides/search-gallery' ),
					],
				],
			],
		],
	];

	return $labels;

}

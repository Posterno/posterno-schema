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

	add_submenu_page( 'edit.php?post_type=listings', esc_html__( 'Listings Schema Settings', 'posterno' ), __( 'Schema', 'posterno' ), 'manage_options', 'posterno-listings-schema', 'pno_schema_admin_listings_schema_page' );

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
		'plugin_url'             => PNO_PLUGIN_URL,
		'schema'                 => pno_get_allowed_schemas(),
		'ajax'                   => admin_url( 'admin-ajax.php' ),
		'nonce'                  => wp_create_nonce( 'pno_create_listing_schema' ),
		'getSchemasNonce'        => wp_create_nonce( 'pno_get_listings_schemas' ),
		'editSchemaNonce'        => wp_create_nonce( 'pno_get_listing_schema' ),
		'saveListingSchemaNonce' => wp_create_nonce( 'pno_save_listing_schema' ),
		'deleteSchemaNonce'      => wp_create_nonce( 'pno_delete_schema' ),
		'listing_types'          => pno_get_listings_types_for_association(),
		'import_url'             => admin_url( 'edit.php?post_type=listings&page=schema_importer' ),
		'export_url'             => admin_url( 'edit.php?post_type=listings&page=schemas_exporter' ),
		'labels'                 => [
			'import'         => esc_html__( 'Import' ),
			'export'         => esc_html__( 'Export' ),
			'documentation'  => esc_html__( 'Documentation', 'posterno' ),
			'add'            => esc_html__( 'Add new schema', 'posterno' ),
			'back'           => esc_html__( 'Schema list', 'posterno' ),
			'setup'          => esc_html__( 'Setup new schema', 'posterno' ),
			'readmore'       => esc_html__( 'Read more', 'posterno' ),
			'success_create' => esc_html__( 'New schema successfully created.', 'posterno' ),
			'listing'        => [
				'title' => esc_html__( 'Posterno listings schema editor', 'posterno' ),
			],
			'structured'     => [
				'step1_title'       => esc_html__( 'Create new schema', 'posterno' ),
				'step1_description' => esc_html__( 'Structured data is code in a specific format, written in such a way that search engines use it to display search results in a specific and much richer way.', 'posterno' ),
				'step1_lists'       => [
					[
						'text' => esc_html__( 'If you’re just getting started, visit the article "Understand how structured data works." on Google.com', 'posterno' ),
						'url'  => esc_url( 'https://developers.google.com/search/docs/guides/intro-structured-data' ),
					],
					[
						'text' => esc_html__( 'Explore the search gallery on Google.com to see how Google uses structured data to display search results in a richer way.', 'posterno' ),
						'url'  => esc_url( 'https://developers.google.com/search/docs/guides/search-gallery' ),
					],
					[
						'text' => esc_html__( 'For detailed information about each available schema type, please refer to the schema.org website.', 'posterno' ),
						'url'  => esc_url( 'https://schema.org/docs/full.html' ),
					],
				],
			],
			'settings'       => [
				'where'         => [
					'label'  => esc_html__( 'Where to apply the schema?', 'posterno' ),
					'global' => esc_html__( 'Globally (all listings)', 'posterno' ),
					'type'   => esc_html__( 'Specific listing type(s)', 'posterno' ),
				],
				'schemas'       => [
					'label'       => esc_html__( 'Select schema', 'posterno' ),
					'description' => esc_html__( 'Select an example schema from the list or leave blank to start with empty json.', 'posterno' ),
				],
				'listing_types' => [
					'label'     => esc_html__( 'Select listing type(s)', 'posterno' ),
					'not_found' => esc_html__( 'No listing types have been found. Add a listing type first and then create the schema.', 'posterno' ),
				],
			],
			'table'          => [
				'name'          => esc_html__( 'Schema name', 'posterno' ),
				'mode'          => esc_html__( 'Type', 'posterno' ),
				'listing_types' => esc_html__( 'Listing types', 'posterno' ),
				'actions'       => esc_html__( 'Actions', 'posterno' ),
				'not_found'     => esc_html__( 'No schema yet, click the button above to add a schema.', 'posterno' ),
				'edit'          => esc_html__( 'Edit', 'posterno' ),
				'delete'        => esc_html__( 'Delete', 'posterno' ),
				'save'          => esc_html__( 'Save schema', 'posterno' ),
			],
			'schema_edit'    => [
				'title'           => esc_html__( 'Schema properties', 'posterno' ),
				'title_edit'      => esc_html__( 'Edit schema', 'posterno' ),
				'schema_url'      => esc_html__( 'Read guidelines for this schema', 'posterno' ),
				'saved'           => esc_html__( 'Schema successfully saved.', 'posterno' ),
				'confirm_delete'  => esc_html__( 'Are you sure you want to delete this schema?', 'posterno' ),
				'deleted_message' => esc_html__( 'Schema successfully deleted.', 'posterno' ),
				'props_tab'       => esc_html__( 'Customize properties', 'posterno' ),
				'fields'          => esc_html__( 'Available fields', 'posterno' ),
			],
		],
	];

	return $labels;

}

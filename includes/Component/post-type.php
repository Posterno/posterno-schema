<?php
/**
 * Create a post type for the schemas.
 *
 * @package     posterno-schema
 * @copyright   Copyright (c) 2018, Pressmodo, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.1.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Registers a new post type to store schema.
 *
 * @return void
 */
function pno_setup_schema_post_type() {

	$labels = array(
		'name'              => esc_html__( 'Schema', 'posterno' ),
		'singular_name'     => esc_html__( 'Schema', 'posterno' ),
		'menu_name'         => esc_html__( 'Schema', 'posterno' ),
		'name_admin_bar'    => esc_html__( 'Schema', 'posterno' ),
		'archives'          => esc_html__( 'Schema', 'posterno' ),
		'attributes'        => esc_html__( 'Item Attributes', 'posterno' ),
		'parent_item_colon' => esc_html__( 'Parent Item:', 'posterno' ),
		'all_items'         => esc_html__( 'All schema', 'posterno' ),
	);
	$args   = array(
		'label'               => esc_html__( 'Schema', 'posterno' ),
		'labels'              => $labels,
		'supports'            => array( 'title' ),
		'hierarchical'        => false,
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => false,
		'menu_position'       => 5,
		'show_in_admin_bar'   => false,
		'show_in_nav_menus'   => false,
		'can_export'          => false,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'query_var'           => false,
		'rewrite'             => false,
		'capability_type'     => 'page',
		'show_in_rest'        => false,
	);
	register_post_type( 'pno_schema', $args );

}
add_action( 'init', 'pno_setup_schema_post_type', 0 );

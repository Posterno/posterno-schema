<?php
/**
 * Component bootstrap process.
 *
 * @package     posterno-schema
 * @copyright   Copyright (c) 2018, Pressmodo, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

namespace PNO\Schema\Frontend;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

function inject_schema_markup() {

	$schema = \PNO\Schema::create( 'Product' );

}
add_action( 'wp_footer', __NAMESPACE__ . '\\inject_schema_markup' );

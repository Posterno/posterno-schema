<?php
/**
 * Component bootstrap process.
 *
 * This file bootstraps parts of the component that can't be autoloaded. We
 * define any global constants here and load any additional functions files.
 *
 * @package     posterno-schema
 * @copyright   Copyright (c) 2018, Pressmodo, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Define the directory path to the framework. This shouldn't need changing
// unless doing something really out there or just for clarity.
if ( ! defined( 'PNO_SCHEMA_DIR' ) ) {
	define( 'PNO_SCHEMA_DIR', __DIR__ );
}

// Check if the framework has been bootstrapped. If not, load the bootstrap files
// and get the framework set up.
if ( ! defined( 'PNO_SCHEMA_BOOTSTRAPPED' ) ) {
	define( 'PNO_SCHEMA_BOOTSTRAPPED', true );
}

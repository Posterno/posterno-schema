<?php
/**
 * The class that loads the whole schema component.
 *
 * @package     posterno-schema
 * @copyright   Copyright (c) 2018, Pressmodo, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

namespace PNO;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Bootstraps the schema component.
 */
class SchemaComponent {

	/**
	 * The single instance of the class.
	 *
	 * @var self
	 */
	private static $instance = null;

	/**
	 * Allows for accessing single instance of class. Class should only be constructed once per call.
	 *
	 * @static
	 * @return self Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Load parts of the component.
	 *
	 * @return void
	 */
	public function init() {

		$this->setup_files();

	}

	/**
	 * Load all required files when they're required.
	 *
	 * @return void
	 */
	public function setup_files() {

		// Admin.
		if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
			$this->setup_admin();
		}

	}

	/**
	 * Load all files for the admin panel.
	 *
	 * @return void
	 */
	public function setup_admin() {

		require_once PNO_SCHEMA_DIR . '/admin/admin-pages.php';

	}

}


<?php
/**
 * Handles injection of the json-ld tag within listings pages.
 *
 * @package     posterno_schema
 * @copyright   Copyright (c) 2018, Pressmodo, LLC
 * @license     http://opensource.org/licenses/gpl_2.0.php GNU Public License
 */

namespace PNO\SchemaOrg\Tag;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Load structured data tag when required within listings pages.
 */
class Listing {

	/**
	 * Hook into WordPress.
	 *
	 * @return void
	 */
	public function init() {

		add_action( 'wp_footer', [ $this, 'inject_tag' ] );

	}

	/**
	 * Determine if we're on a page where we should load the jsonld tags.
	 *
	 * @return boolean
	 */
	private function should_load() {

		return is_singular( [ 'listings' ] );

	}

	/**
	 * Load the structured data tag within the footer.
	 *
	 * @return void
	 */
	public function inject_tag() {

		if ( ! $this->should_load() ) {
			return;
		}

	}

}

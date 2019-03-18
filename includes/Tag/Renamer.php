<?php
/**
 * Handles properties names changes in preparation of the output.
 *
 * @package     posterno_schema
 * @copyright   Copyright (c) 2018, Pressmodo, LLC
 * @license     http://opensource.org/licenses/gpl_2.0.php GNU Public License
 */

namespace PNO\SchemaOrg\Tag;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Convert properties names from their name into the database,
 * to the name required for json-ld compatibility.
 */
class Renamer {

	/**
	 * Rename a property to the json-ld equivalent.
	 *
	 * @param string $prop the property to rename.
	 * @return string
	 */
	public static function rename( $prop ) {

		return $prop;

	}

}

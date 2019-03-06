<?php
/**
 * Helper class to print the schema block.
 *
 * @package     posterno-schema
 * @copyright   Copyright (c) 2018, Pressmodo, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

namespace PNO;

use InvalidArgumentException;
use PNO\Schema\Contracts\ContextTypeInterface;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Create a schema block.
 */
class Schema {

	protected $context = null;

	public function __construct( $context, array $data = [] ) {
		$class         = $this->get_context_type_class( $context );
		$this->context = new $class( $data );
	}

	public static function create( $context, array $data = [] ) {
		return new static( $context, $data );
	}

	public function get_properties() {
		return array_filter( $this->context->get_properties() );
	}

	public function generate() {
		$properties = $this->get_properties();
		return $properties ? '<script type="application/ld+json">' . wp_json_encode( $properties, JSON_UNESCAPED_UNICODE ) . '</script>' : '';
	}

	protected function get_context_type_class( $name ) {

		if ( class_exists( $name ) ) {
			return $name;
		}

		$class = ucwords( str_replace( [ '-', '_' ], ' ', $name ) );
		$class = '\\PNO\\Schema\\ContextTypes\\' . str_replace( ' ', '', $class );

		if ( class_exists( $class ) ) {
			return $class;
		}

		throw new InvalidArgumentException( sprintf( 'Undefined context type: "%s"', $name ) );

	}

	public function __toString() {
		return $this->generate();
	}

}


<?php

namespace PNO\SchemaOrg;

use DateTime;
use ReflectionClass;
use DateTimeInterface;
use PNO\SchemaOrg\Exceptions\InvalidProperty;

abstract class BaseType implements Type, \ArrayAccess, \JsonSerializable {

	/** @var array */
	protected $properties = [];

	public function getContext() {
		return 'https://schema.org';
	}

	public function getType() {
		return ( new ReflectionClass( $this ) )->getShortName();
	}

	public function setProperty( $property, $value ) {
		$this->properties[ $property ] = $value;

		return $this;
	}

	public function addProperties( $properties ) {
		foreach ( $properties as $property => $value ) {
			$this->setProperty( $property, $value );
		}

		return $this;
	}

	public function doIf( $condition, $callback ) {
		if ( $condition ) {
			$callback( $this );
		}
		return $this;
	}

	public function getProperty( $property, $default = null ) {
		return isset( $this->properties[ $property ] ) ? $this->properties[ $property ] : $default;
	}

	public function getProperties() {
		return $this->properties;
	}

	public function offsetExists( $offset ) {
		return array_key_exists( $offset, $this->properties );
	}

	public function offsetGet( $offset ) {
		return $this->getProperty( $offset );
	}

	public function offsetSet( $offset, $value ) {
		$this->setProperty( $offset, $value );
	}

	public function offsetUnset( $offset ) {
		unset( $this->properties[ $offset ] );
	}

	public function toArray() {
		$properties = $this->serializeProperty( $this->getProperties() );

		return [
			'@context' => $this->getContext(),
			'@type'    => $this->getType(),
		] + $properties;
	}

	protected function serializeProperty( $property ) {
		if ( is_array( $property ) ) {
			return array_map( [ $this, 'serializeProperty' ], $property );
		}

		if ( $property instanceof Type ) {
			$property = $property->toArray();
			unset( $property['@context'] );
		}

		if ( $property instanceof DateTimeInterface ) {
			$property = $property->format( DateTime::ATOM );
		}

		if ( method_exists( $property, '__toString' ) ) {
			$property = (string) $property;
		}

		if ( is_object( $property ) ) {
			throw new InvalidProperty();
		}

		return $property;
	}

	public function toScript() {
		return '<script type="application/ld+json">' . json_encode( $this->toArray(), JSON_UNESCAPED_UNICODE ) . '</script>';
	}

	public function jsonSerialize() {
		return $this->toArray();
	}

	public function __call( $method, array $arguments ) {

		$args = isset( $arguments[0] ) ? $arguments[0] : '';

		return $this->setProperty( $method, $args );
	}

	public function __toString() {
		return $this->toScript();
	}
}
